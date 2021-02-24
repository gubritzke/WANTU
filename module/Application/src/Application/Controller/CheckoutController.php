<?php
namespace Application\Controller;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;
use Naicheframework\Shopping\Model\OrderMultiple;
use Naicheframework\Shopping\Payment\PaymentAbstract;
use Application\Model\ModelPlanos;
use Application\Model\ModelLogin;
use Application\Model\ModelPlanosUsuario;
use Application\Model\ModelCupom;

class CheckoutController extends GlobalController
{
	/**
	 * @var PaymentAbstract
	 */
	protected $payment;
	
	private function init()
	{
		
		//iniciar serviço de pagamento
		$this->payment = new \Naicheframework\Shopping\Payment\PaymentMoipSplit($this->layout()->config_payment['moip']);
		
		//definir ambiente do serviço de pagamento
		if( in_array($this->layout()->config_host['env'], ['local','homolog']) )
		{
			$this->payment->setSandbox(true);
		}
		
		
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
	}
	
	/**
	 * @todo Naiche MONTAR TELA
	 */
	
	public function planosAction()
	{
		
		$this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
		$this->head->setCss('../js/extensions/jquery.tooltipster/dist/css/tooltipster.bundle.min.css');
		
		$filter = array();
		$filter['expr'] = 'login.id_login = "' . $this->layout()->me->id_login. '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['user'] = $modellogin->get2($filter)->current();
		//echo '<pre>'; print_r($this->view['user']->id_plano); exit;
		
		//view
		$view = new ViewModel($this->view);
		return $view;
	}
	
    public function pagamentoAction()
    {
    	$this->init();

    	$get = $this->params()->fromQuery();
    	
    	//seleciona os dados do usuário
    	$modelplano = new ModelPlanos($this->tb, $this->adapter);
    	$filter['expr'] = 'tipo = "' . $get['tipo']. '"';
    	$result = $modelplano->get($filter);
    	$this->view['plano'] = $result;
    	$this->view['plano'] = $this->view['plano']->current();
    	
    	//echo '<pre>'; print_r($get['tipo']); exit;
    	
    	$filter = array();
    	$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login. '"';
    	$modellogin = new ModelLogin($this->tb, $this->adapter);
    	$this->view['user'] = $modellogin->get($filter)->current();
    	$user = $this->view['user'];
    	
    	//define desconto do plano se tiver plano ativo
    	$filter = array();
    	$filter['expr'] = 'login.id_login = "' . $this->layout()->me->id_login. '"';
    	$modellogin = new ModelLogin($this->tb, $this->adapter);
    	$this->view['user_plano'] = $modellogin->get2($filter)->current();
    	$user_plano = $this->view['user_plano'];
    	
    	if ($user_plano->id_plano == 1 && $get['tipo'] == 2){
    		$this->view['plano']->valor = 99.90;
		}
		if ($user_plano->id_plano == 1 && $get['tipo'] == 3){
			$this->view['plano']->valor = 184.90;
		}
		if ($user_plano->id_plano == 2 && $get['tipo'] == 3){
			$this->view['plano']->valor = 99.90;
		}
    	
		//echo '<pre>'; print_r($this->view['plano']->valor); exit;
    	
    	// Arruma data
    	$data = explode("/", $user['nascimento']);
    	$data = $data[2] . "-" . $data[1] . "-" . $data[0];
    	
    	//echo '<pre>'; print_r($data); exit;
    	
    	$this->view['data_ani'] = $data;
    	
		//selecionar os dados do usuário
		$this->view['usuario'] = $this->layout()->me;
        
		//definir cupom
		$this->view['cupom'] = \Naicheframework\Session\Session::get('cupom');
		
		
		//definir desconto
		$cupom = \Naicheframework\Session\Session::get('cupom');
		$desconto = 0;
		if( !empty($cupom) )
		{
			$tipo = $cupom->tipo;
			$total = $this->view['plano']->valor;
			$desconto = $cupom->valor;
			if( $tipo == 'porcentagem' )
			{
				$desconto = $total * $desconto / 100;
			}
			
		}
		$this->view['desconto_calc'] = $desconto;
		$desconto_calc = $this->view['desconto_calc'];
		
		//echo '<pre>'; print_r($this->view['desconto_calc']); exit;
		
		
		
		//definir parcelas
		$this->view['parcelas'] = $this->getParcelas($this->view['plano']->valor, $desconto_calc);
		
		//echo '<pre>'; print_r($this->view['cupom']); exit;
		
		//MOIP - public key
		$this->view['public_key'] = $this->payment->getPublicKey();
		
		//libs
		$this->head->setJs("//assets.moip.com.br/v2/moip.min.js", true);
		$this->head->addCard();
		
		//view
		$view = new ViewModel($this->view);
		return $view;
    }
    
    /**
     * @todo Naiche MONTAR TELA
     */
    public function sucessoAction()
    {
    	//id do usuario
    	$id_usuario = $this->layout()->me->id_login;
    	
    	//echo '<pre>'; print_r($id_usuario); exit;
    	
    	//SLECIONA PLANOS
    	$filter = array();
    	$filter['expr'] = 'planos_usuario.id_login = "'. $id_usuario.'"';
    	$filter['limit'] = '1';
    	$filter['order'] = 'criado DESC';
    	$model = new ModelPlanosUsuario($this->tb, $this->adapter);
    	$this->view['pedido'] = $model->get($filter)->current();

    	$this->view['pedido']->gateway= json_decode($this->view['pedido']->gateway);
    	
    	//echo '<pre>'; print_r($this->view['pedido']); exit;
    	
    	//selecionar os dados do usuário
    	$this->view['usuario'] = $this->layout()->me;
    	
    	//libs
    	$this->head->addCard();
    	
    	//view
    	return new ViewModel($this->view);
    }
	
    protected function pagamentoConfirmar()
    {
    	try 
    	{
    	    //==================================================================
    	    //validações necessárias antes de processar o pagamento
    	    //==================================================================
    	    
    		//selecionar o plano
    		//@todo pegar o plano escolhido
    		
    		$get = $this->params()->fromQuery();
    		
    		//seleciona os dados do usuário
    		$modelplano = new ModelPlanos($this->tb, $this->adapter);
    		$filter['expr'] = 'tipo = "' . $get['tipo']. '"';
    		$result = $modelplano->get($filter);
    		$this->view['plano'] = $result;
    		$plano = $this->view['plano']->current();
    		
    		$filter = array();
    		$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login. '"';
    		$modellogin = new ModelLogin($this->tb, $this->adapter);
    		$user = $modellogin->get($filter)->current();
    		$this->view['user_data'] = $user;
    		
    		//define desconto do plano se tiver plano ativo
    		$filter = array();
    		$filter['expr'] = 'login.id_login = "' . $this->layout()->me->id_login. '"';
    		$modellogin = new ModelLogin($this->tb, $this->adapter);
    		$this->view['user_plano'] = $modellogin->get2($filter)->current();
    		$user_plano = $this->view['user_plano'];
    		
    		if ($user_plano->id_plano == 1 && $get['tipo'] == 2){
    			$plano['valor'] = 99.90;
    		}
    		if ($user_plano->id_plano == 1 && $get['tipo'] == 3){
    			$plano['valor'] = 184.90;
    		}
    		if ($user_plano->id_plano == 2 && $get['tipo'] == 3){
    			$plano['valor'] = 99.90;
    		}
    		
			// Arruma data 
    		$data = explode("/", $user['nascimento']);
    		$data = $data[2] . "-" . $data[1] . "-" . $data[0];
    		
    		//selecionar usuario
    		$usuario = $this->layout()->me;
    		
    		//informações do cartão de crédito
    		$creditcard = $this->params()->fromPost();
    		
    		//validar informações do cartão de crédito
    		if( !empty($creditcard['cc_hash']) && (empty($creditcard['cc_name']) || empty($creditcard['cc_parcel'])) )
    		{
    			throw new \Exception("Preencha todas as informações do cartão de crédito.", 999);
    		}
    		
    		//definir qual foi o tipo de pagamento
    		$pagamento_tipo = empty($creditcard['cc_hash']) ? 'Boleto' : 'Cartão de crédito';
    		
    		//==================================================================
    		//organizar as informações para o gateway de pagamento
    		//==================================================================
    		
    		//definir serviço de pagamento
    		$this->payment = new \Naicheframework\Shopping\Payment\PaymentMoipSplit($this->layout()->config_payment['moip']);
    		if( in_array($this->layout()->config_host['env'], ['local','homolog']) ) $this->payment->setSandbox(true);
    		
			//definir um código único para essa solicitação de pagamento
			$pagamento_codigo = "Wantu" . strtoupper(uniqid());
			$this->payment->setPaymentId($pagamento_codigo);
			
			//informações do cartão de crédito
			$this->payment->payment_info['hash'] = $creditcard['cc_hash'];
			$this->payment->payment_info['name'] = $creditcard['cc_name'];
			$this->payment->payment_info['type'] = $creditcard['cc_type'];
			$this->payment->payment_info['telefone'] = $creditcard['cc_telefone'];
			$this->payment->payment_info['cpf'] = $creditcard['cc_cpf'];
			$this->payment->payment_info['nascimento'] = $creditcard['cc_nascimento'];
			$parcel = $this->payment->payment_info['parcel'] = $creditcard['cc_parcel'];
			
			//definir item
			$item = new \Naicheframework\Shopping\Model\Item();
			$item->setId($plano['id_planos']);
			$item->setDescricao($plano['descricao']);
			$item->setQuantidade(1);
			$item->setPreco($plano['valor']);
			
			//definir pedido
			$order = new \Naicheframework\Shopping\Model\Order();
			$order->addItem($item);
			
			//echo $desconto; exit;
			//definir pedido multiplo
			$orderMultiple = new \Naicheframework\Shopping\Model\OrderMultiple();
			$orderMultiple->addOrder($order);
			
			//definir desconto
			$cupom = \Naicheframework\Session\Session::get('cupom');
			$desconto = 0;
			if( !empty($cupom) )
			{
				$tipo = $cupom->tipo;
				$total = $plano['valor'];
				$desconto = $cupom->valor;
				if( $tipo == 'porcentagem' )
				{
					$desconto = $total * $desconto / 100;
				}
				
				$modelcupom = new ModelCupom($this->tb, $this->adapter);
				$modelcupom->save(['usado' => $cupom->usado + 1], $cupom->id_cupom);
				
				$orderMultiple->setDesconto($desconto);

				//echo '<pre>'; print_r($orderMultiple); exit;
				
			}
			
			//definir adicional
// 			if( !empty($creditcard['cc_parcel']) && $creditcard['cc_parcel'] > 1 )
// 			{
// 				$parcelas = $this->getParcelas($plano['valor'], $desconto, false);
// 				$parcela = $creditcard['cc_parcel'];
// 				$parcela = $parcelas[$parcela];
// 				$adicional = $plano['valor'] - $parcela;
// 				$orderMultiple->setAdicional($adicional);
			
// 				echo '<pre>'; print_r($parcela); exit;
// 			}
			
			//echo '<pre>'; print_r($data); exit;

			//dados do comprador
			$recipient = new \Naicheframework\Shopping\Model\Recipient();
			$recipient->setNome($user['nome']);
			$recipient->setEmail($user['email']);
			$recipient->setTelefone($user['telefone']);
			$recipient->setDocumento($user['cpf']);
			$recipient->setNascimento($data);
			$recipient->setCep($user['cep']);
			$recipient->setLogradouro($user['endereco']);
			$recipient->setNumero($user['numero']);
			$recipient->setComplemento($user['complemento']);
			$recipient->setBairro($user['bairro']);
			$recipient->setCidade($user['cidade']);
			$recipient->setEstado($user['estado']);
			$recipient->setPais('Brasil');
			$orderMultiple->setComprador($recipient);
			
			//echo '<pre>'; print_r($orderMultiple); exit;
			
  	    	//definir nome na fatura do cartão
  	    	$orderMultiple->setNomeNaFatura('WANTU');
  	    	
  	    	//verificar se total é ZERO
  	    	$total_final = ($orderMultiple->getTotal() - $orderMultiple->getDesconto());
  	    	
  	    	if( $total_final > 0 )
  	    	{
	  	    	//enviar informações para o sistema de pagamento
	  	    	$paymentResult = $this->payment->requestPaymentMultiple($orderMultiple);
	  	    	$id = $paymentResult->getId();
	  	    	$status = $paymentResult->getStatus();
	  	    	
	  	    	//validar o retorno do pagamento
	  	    	if( empty($id) || !in_array($status, ["0","1","2"]) )
	  	    	{
	  	    		throw new \Exception("Houve um problema ao efetuar a transação, tente novamente ou utilize outro meio de pagamento!");
	  	    	}
	  	    	if( $status == "2" )
	  	    	{
	  	    		throw new \Exception("Seu pagamento não foi autorizado, tente novamente ou utilize outro meio de pagamento!");
	  	    	}
	  	    	
	  	    	//informações de pagamento
	  	    	$info = $paymentResult->toArray();
	  	    	$info['hash'] = $this->payment->payment_info['hash'];
	  	    	$info['name'] = $this->payment->payment_info['name'];
	  	    	$info['parcel'] = $this->payment->payment_info['parcel'];
	  	    	$info['type'] = $this->payment->payment_info['type'];
	  	    	
  	    	} else {
  	    		
  	    		$status = 1;
  	    		$info = array();
  	    		
  	    	}
  	    	
	    	
	    	//@todo Naiche SALVAR NO BANCO
	    	//==================================================================
	    	//registrar pedido no banco de dados
	    	//==================================================================
	    	
			//inserir pedido no banco de dados
	    	$data= array();
	    	$data['total'] = $plano['valor'];
			$data['codigo'] = $pagamento_codigo;
			$data['tipo'] = $pagamento_tipo;
			$data['gateway'] = json_encode($info);
			$data['id_plano'] = $plano['id_planos'];
			$data['adicional'] = $adicional;
	        $data['status'] = $status;
	        $data['desconto'] = $desconto;
	        $data['plano'] = $plano['descricao'];
	        $data[id_login] = $this->layout()->me->id_login;
			
	        //@todo fazer salvar
	        
	        $modelusuarioplano = new ModelPlanosUsuario($this->tb, $this->adapter);
	        $modelusuarioplano ->save($data);
	        
 	    	//definir URL de retorno após pagamento
 	    	$url = $_SERVER['HTTP_HOST'];
 	    	$protocol = ($_SERVER['HTTPS'] == true) ? 'https://' : 'http://';
 	    	$redirect = $protocol . $url . '/checkout/sucesso';
 	    	
	    	//redirecionamento
			return $this->redirect()->toUrl($redirect);
			
    	} catch( \Exception $e ){
    		
    	    //registrar log de erro
    	    if( $e->getCode() != 999 )
    	    {
    	       \Naicheframework\Log\Log::error("Erro no processo de pagamento", ['exception' => $e->getMessage()]); 
    	    }
    	    
    	    //mensagem de informação
    		$this->flashMessenger()->addInfoMessage($e->getMessage());
    		
    		//redirecionamento
    		return $this->redirect()->toUrl('/checkout/pagamento');
    		
    	}
    }
    
    
    protected function addcupom()
    {
    	try
    	{
    		$id = $this->params()->fromQuery('tipo');
			$cupom = $this->params()->fromPost('cupom');    	
			
			//selecionar cupom
			$where = ' cupom.cupom = "' . $cupom . '"';
			$where .= ' AND cupom.status = "ativo"';
			$where .= ' AND cupom.quantidade > cupom.usado';
			$where .= ' AND SUBSTRING(cupom.validade_de, 1, 10) <= "' . date('Y-m-d') . '"';
			$where .= ' AND SUBSTRING(cupom.validade_ate, 1, 10) >= "' . date('Y-m-d') . '"';
    		$model = new ModelCupom($this->tb, $this->adapter);
    		$return= $model->get(['expr' => $where])->current();
    		
    		if (empty($return)){
    			throw new \Exception('Cupom invalido');
    		}
    		
    		\Naicheframework\Session\Session::set('cupom', $return);
    		//echo '<pre>'; print_r($return); exit; 
    		
    	} catch( \Exception $e ){
    		
    	
    		
    		//mensagem de informação
    		$this->flashMessenger()->addInfoMessage($e->getMessage());
    		
    		
    		
    	}
    	//redirecionamento
    		return $this->redirect()->toUrl("/checkout/pagamento?tipo=$id");
    }
    
    
    protected function delcupom()
    {
    		$id = $this->params()->fromQuery('tipo');
    		\Naicheframework\Session\Session::unset('cupom');
    		return $this->redirect()->toUrl("/checkout/pagamento?tipo=$id");
    }
    
    private function getParcelas($total, $desconto, $return_string=true)
    {
    	$parcels = array();
    	
    	if( $desconto > 0 )
    	{
    		$desconto = $total * $desconto / 100;
    	}
    	
    	for ($i = 1; $i <= 12; $i++) {
    		
    		if( $return_string === true )
    		{
    			$parcels[$i] = $i.'x R$ '.\Naicheframework\Helper\Convert::toReal(($total - $desconto) / $i);
    		} else {
    			
    			$parcels[$i] = ($total - $desconto) / $i;
    			
    		}
    		
    		
    	}
    	
//     	$parcels[2] = $total / 2;
//     	$parcels[3] = $total / 3; 
//     	$parcels[4] = $total / 4;
//     	$parcels[5] = $total / 5;
//     	$parcels[6] = $total / 6;
//     	$parcels[7] = $total / 7;
//     	$parcels[8] = $total / 8;
//     	$parcels[9] = $total / 9;
//     	$parcels[10] = $total / 10;
//     	$parcels[11] = $total / 11;
//     	$parcels[12] = $total / 12;
//     	$parcels[4] = $total + ($total * 0.2 / 100);
    	
    	return $parcels;
    	
    }
}