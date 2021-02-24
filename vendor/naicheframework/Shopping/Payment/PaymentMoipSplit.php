<?php
namespace Naicheframework\Shopping\Payment;
require_once(dirname(__FILE__) . '/library/Requests/Requests.php');
require_once(dirname(__FILE__) . '/library/autoload.php');

use Moip\Moip;
use Moip\Auth\BasicAuth;
use Moip\Auth\OAuth;
use Naicheframework\Shopping\Model\Order;
use Naicheframework\Shopping\Model\OrderMultiple;
use Naicheframework\Shopping\Model\PaymentResult;

/**
 * @NAICHE | Vitor Deco
 */
class PaymentMoipSplit extends PaymentAbstract
{
	//chave pública
	protected $public_key = null;
	
	//chave JS para assinaturas
	protected $key_js = null;
	
	//token da conta
	protected $token = null;
	
	//key da conta
	protected $key = null;
	
	//moip ID
	protected $moip_id = null;
	
	//instancia da class
	private $payment;

	public function __construct(array $config)
	{
	    //definir configurações
	    foreach( $config as $key => $value )
	    {
	        $this->$key = $value;
	    }
	    
	    //autoloader
	    \Requests::register_autoloader();
	    
	    //define o tipo de pagamento
	    parent::__construct("moip-split");
	}
	
	public function setSandbox($bool=true)
	{
	    parent::setSandbox($bool);
	    return $this;
	}
	
	public function getPublicKey()
	{
	    return $this->public_key;
	}
	
	/**
	 * retorna o status do pagamento
	 * @param integer $status
	 */
	public static function getStatus($status)
	{
		switch( $status )
		{
			case self::STATUS_PENDING:
				return "Aguardando pagamento"; break;
	
			case self::STATUS_PAID:
				return "Pagamento realizado"; break;
					
			case self::STATUS_CANCELED:
				return "Cancelado"; break;
		}
	}
	
	/**
	 * retorna o link do boleto
	 * @param string $code
	 * @param bool $is_sandbox
	 */
	public static function getBoletoLink($code, $is_sandbox=false, $link=null)
	{
	    if( !empty($link) )
	    {
	        return $link;

	    } else {
    		$env = ($is_sandbox) ? "-sandbox" : null;
    		return "https://checkout" . $env . ".moip.com.br/boleto/" . $code . "/print";
	    }
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Naicheframework\Shopping\Payment\PaymentAbstract::getTransaction()
	 */
	public function getTransaction($code)
	{
		try {
			//define qual ambiente foi escolhido
			$sandbox = ($this->is_sandbox) ? Moip::ENDPOINT_SANDBOX : Moip::ENDPOINT_PRODUCTION;
			$this->payment = new Moip(new BasicAuth($this->token, $this->key), $sandbox);
			//echo'<pre>'; print_r($this->payment); exit;
			
			//selecionar os dados do pagamento
			$payment = $this->payment->payments()->get($code);
			//echo'<pre>'; print_r($payment); exit;
            
			//define o status
			$status = $payment->getStatus();
			$status = ($status == "CANCELLED") ? self::STATUS_CANCELED : (($status == "AUTHORIZED") ? self::STATUS_PAID : self::STATUS_PENDING);
			
			//informações sobre a transação
			$transaction = current($payment->getPayments())->fundingInstrument;
			
			//array para retornar
			$result = (object)array(
				'reference' => $payment->getId(),
				'code' => $payment->getId(),
				'date' => $payment->getUpdatedAt(),
				'status' => $status,
				'transaction' => $transaction,
			);
			
			return $result;

        } catch (\Moip\Exceptions\UnautorizedException $e) {
            
            //StatusCode 401
            throw new \Exception($e->getMessage());
            
        } catch (\Moip\Exceptions\ValidationException $e) {
            
            //StatusCode entre 400 e 499 (exceto 401)
            throw new \Exception($e->__toString());
            
        } catch (\Moip\Exceptions\UnexpectedException $e) {
            
            //StatusCode >= 500
            throw new \Exception($e->getMessage());
        }    
        
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Naicheframework\Shopping\Payment\PaymentAbstract::requestPayment()
	 */
	public function requestPayment(Order $order)
	{
	    die('função requestPayment não disponível no momento!');
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Naicheframework\Shopping\Payment\PaymentAbstract::requestPaymentMultiple()
	 */
	public function requestPaymentMultiple(OrderMultiple $orderMultiple)
	{
        try
        {
			//definir ambiente do gateway
			$sandbox = ($this->is_sandbox) ? Moip::ENDPOINT_SANDBOX : Moip::ENDPOINT_PRODUCTION;
			$this->payment = new Moip(new BasicAuth($this->token, $this->key), $sandbox);
			
            //criar multipedidos
            $multiorder = $this->payment->multiorders()->setOwnId($this->getPaymentId());
            
            //criar comprador
            $recipient = $orderMultiple->getComprador();
            $customer_name = \Naicheframework\Helper\Convert::removeEspecialChars($recipient->getNome(), true);
            $customer_street = \Naicheframework\Helper\Convert::removeEspecialChars($recipient->getLogradouro(), true);
            $customer_district = \Naicheframework\Helper\Convert::removeEspecialChars($recipient->getBairro(), true);
            $customer = $this->payment->customers()->setOwnId(uniqid())
            ->setFullname($customer_name)
            ->setEmail($recipient->getEmail())
            ->setTaxDocument($recipient->getDocumento(), $recipient->getDocumentoTipo())
            ->setPhone($recipient->getTelefoneDDD(), $recipient->getTelefoneNumber())
            ->setBirthDate($recipient->getNascimento())
            ->addAddress(
                'SHIPPING',
                $customer_street,
                $recipient->getNumero(),
                $customer_district,
                $recipient->getCidade(),
                $recipient->getEstado(),
                $recipient->getCep(),
                $recipient->getComplemento()
            )->create();
            
			//loop nos pedidos
            foreach( $orderMultiple->getOrder() as $order )
			{
			    //criar pedido e adicionar uma identificação única do pedido
			    $multiorderItem = $this->payment->orders()->setOwnId($this->getPaymentId());
			    
			    //adicionar comprador ao pedido
				$multiorderItem->setCustomer($customer);
			    
				//adicionar os itens ao pedido
				foreach( $order->getItem() as $item )
				{
					$product = \Naicheframework\Helper\Convert::removeEspecialChars($item->getDescricao(), true);
					$quantity = (int)$item->getQuantidade();
					$detail = $item->getId();
					$price = (int)ceil($item->getPreco() * 100);
					$multiorderItem->addItem($product, $quantity, $detail, $price);
				}
				
				//agrupar fretes disponíveis para seleciar o valor
				$orderMultipleTemp = new \Naicheframework\Shopping\Model\OrderMultiple();
				$orderMultipleTemp->addOrder($order);
				$servicePackage = new \Naicheframework\Shopping\Service\ServicePackage();
				$shipments = $servicePackage->agruparFretes($orderMultipleTemp);

				//adicionar custo de entrega
				$shipping = $shipments->getItemByService($order->getServico(), true);
				if( !empty($shipping->getValor()) )
				{
					$value = (int)ceil($shipping->getValor() * 100);
					$multiorderItem->setShippingAmount($value);
				}
				
				//definir subtotal do pedido
				$subtotal = $order->getTotal();
				
				//calcular valor de desconto individual dos itens
				$desconto = $order->getItemDesconto();
				
				//calcular valor de desconto do pedido
				if( !empty($orderMultiple->getDesconto()) )
				{
				    //dividir o desconto pela quantidade de pedidos
					$value = ($orderMultiple->getDesconto() / $orderMultiple->countOrder());
					
					//somar ao desconto
					$desconto += $value;
				}
				
				//adicionar valor de desconto
				if( !empty($desconto) )
				{
				    //subtrair desconto do subtotal para calcular a comissão
				    $subtotal -= $desconto;
				    
				    //converter para número inteiro e adiciontar ao gateway
				    $desconto = (int)ceil($desconto * 100);
				    $multiorderItem->setDiscount($desconto);
				}
				
				//definir as comissões
				$comissao = ($subtotal * $order->getComissao() / 100);
				$comissao = round(($comissao + $shipping->getValor()), 2); //adicionar o valor do frete na comissão
				$comissao_vendedor = round(($subtotal + $shipping->getValor() - $comissao), 2);
				$comissao_vendedor = (int)($comissao_vendedor * 100);

				//vincular vendedores
				$multiorderItem->addReceiver($this->moip_id, 'PRIMARY');
				
				//comissão da octoplace
				if( $orderMultiple->hasGatewayCode() && $orderMultiple->hasComissao() )
				{
				    $comissao_octoplace = ($subtotal * $orderMultiple->getComissao() / 100);
				    $comissao_octoplace = (int)(round(($comissao_octoplace), 2) * 100);
				    $multiorderItem->addReceiver($orderMultiple->getGatewayCode(), 'SECONDARY', $comissao_octoplace);
				}
				
				//comissão do vendedor
				if( $order->getComissao() > 0 )
				{
				    $multiorderItem->addReceiver($order->getGatewayCode(), 'SECONDARY', $comissao_vendedor);
				}
				
				//adicionar valor do juros da parcela do cartão de crédito
				if( $orderMultiple->hasAdicional() )
				{
				    //converter para número inteiro e adiciontar ao gateway
				    $adicional = (int)ceil($orderMultiple->getAdicional() * 100);
				    $multiorderItem->setAddition($adicional);
				    $orderMultiple->setAdicional(0);
				}
				
                //add pedido
				$multiorder->addOrder($multiorderItem);
			}
			$multiorder->create();
			//echo'<pre>'; print_r($multiorder); exit;
			
			//processar o pagamento CARTÃO DE CRÉDITO
			if( !empty($this->payment_info['hash']) )
			{
				
				//Arruma telefone
				$phone = str_replace(['(',')','-',' '], '', $this->payment_info['telefone']);
				$phone_ddd = (int)substr($phone, 0, 2);
				$phone_number = (int)substr($phone, 2);
				
				// Limpar cpf
				$documento = str_replace(['.','-'], '', $this->payment_info['cpf']);
				
				// Arruma data
				$date = implode('-', array_reverse(explode('/', $this->payment_info['nascimento'])));
				
				//echo '<pre>'; print_r($this->payment_info); exit;
				
				//dados do dono do cartão
				$customerCreditCard = $this->payment->customers()->setOwnId(uniqid())
				->setFullname($this->payment_info['name'])
				->setEmail($orderMultiple->getComprador()->getEmail())
				->setTaxDocument($documento, $orderMultiple->getComprador()->getDocumentoTipo())
				->setPhone($phone_ddd, $phone_number)
				->setBirthDate($date)
				->create();
				
				//echo '<pre>'; print_r($customerCreditCard); exit;
				
				//dados do cartão
				$hash = $this->payment_info['hash'];
				$installmentCount = (int)$this->payment_info['parcel'];
				$statementDescriptor = $orderMultiple->getNomeNaFatura(); //nome na fatura
				
				//processar pagamento
				$payment = $multiorder->multipayments()
				->setCreditCardHash($hash, $customerCreditCard)
				->setInstallmentCount($installmentCount)
				->setStatementDescriptor($statementDescriptor)
				->execute();
			
			//processar o pagamento BOLETO
			} else {
				
			    //define a url atual
			   	$url = $_SERVER['HTTP_HOST'];
			    
			    //define o protocolo atual
			    $protocol = ($_SERVER['HTTPS'] == true) ? 'https://' : 'http://';
			    
				//dados do boleto
				$logo_uri = $protocol . $url . '/assets/application/img/moip/logo.png';
				$expiration_date = new \DateTime("+4 days");
				$instruction_lines = [];
				
				//processar pagamento
				$payment = $multiorder->multipayments()
				->setBoleto($expiration_date, $logo_uri, $instruction_lines)
				->execute();
			}

			//definir id
			$id = $payment->getId();
			
			//definir status
			$status = $payment->getStatus();
			$status = ($status == "CANCELLED") ? self::STATUS_CANCELED : (($status == "AUTHORIZED") ? self::STATUS_PAID : self::STATUS_PENDING);
			
			//informações sobre a transação
			$transaction = current($payment->getPayments())->fundingInstrument;
			
			//definir link de impressão do boleto
			if( !empty($payment->getHrefPrintBoleto()) )
			{
			    $transaction->print = $payment->getHrefPrintBoleto();
			}
			
			//retorno do pagamento
			$paymentResult = new PaymentResult();
			$paymentResult->setId($id);
			$paymentResult->setStatus($status);
			$paymentResult->setDetail($transaction);
			return $paymentResult;
			
        } catch (\Moip\Exceptions\UnautorizedException $e) {
            //StatusCode 401
            throw new \Exception($e->getMessage());
            
        } catch (\Moip\Exceptions\ValidationException $e) {
            //StatusCode entre 400 e 499 (exceto 401)
            throw new \Exception($e->__toString());
            
        } catch (\Moip\Exceptions\UnexpectedException $e) {
            //StatusCode >= 500
            throw new \Exception($e->getMessage());
        }
	    
	    
	}
	
}