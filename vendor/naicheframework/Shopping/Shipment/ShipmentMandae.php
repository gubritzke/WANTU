<?php
namespace Naicheframework\Shopping\Shipment;
use Naicheframework\Shopping\Model\Package;
use Naicheframework\Shopping\Model\Sender;
use Naicheframework\Shopping\Model\Recipient;
use Naicheframework\Shopping\Model\ShipmentMultiple;
use Naicheframework\Shopping\Model\Shipment;
use Naicheframework\Shopping\Validate\ValidateCalcularFrete;
use Naicheframework\Shopping\Model\Order;
use Naicheframework\Shopping\Model\ShipmentEtiqueta;

/**
 * @NAICHE | Vitor Deco
 */
class ShipmentMandae extends ShipmentAbstract
{
	//método de envio
	const TYPE = "MANDAE";
	
	//nome do método de envio
	const NAME = "Mandaê";
	
	//definir tipos de entrega
	const ENTREGA_SUPERRAPIDO = "Transportadora Super Rápido";
	const ENTREGA_RAPIDO = "Transportadora Rápido";
	const ENTREGA_ECONOMICO = "Transportadora Econômico";
	
	//token
	private $token = null;
	
	//customer ID
	private $customer_id = null;
	
	//class que faz o request na api
	protected $api;
	
	public function __construct(array $config)
	{
	    //definir configurações
	    foreach( $config as $key => $value )
	    {
	        $this->$key = $value;
	    }
	    
		//instancia da class que faz o request na api
		$url = "https://api.mandae.com.br/v2";
		$authorization = "Authorization: " . $this->token;
		$this->api = new \Naicheframework\Api\Request($url, $authorization);
		$this->api->setJsonData(true);
		
		//define o método de envio
		parent::__construct(self::TYPE, self::NAME);
	}
	
	public function setSandbox($bool)
	{
		if( $bool === true )
	    {
	    	$url = "https://sandbox.api.mandae.com.br/v2";
	    	$authorization = "Authorization: " . $this->token;
			$this->api = new \Naicheframework\Api\Request($url, $authorization);
			$this->api->setJsonData(true);
	    }
	    
	    parent::setSandbox($bool);
	    return $this;
	}
	
	public function calcularFrete(Package $package, Sender $sender, Recipient $recipient)
	{
		try 
		{
			//validar campos
			$validate = new ValidateCalcularFrete();
			if( !$validate::isNotEmpty($sender->getCep()) )
			{
				throw new \Exception($validate::ERROR_REQUIRED_REMETENTE_CEP);
			}
			
			if( !$validate::isNotEmpty($recipient->getCep()) )
			{
				throw new \Exception($validate::ERROR_REQUIRED_DESTINATARIO_CEP);
			}
			
			$cond1 = !$validate::isNotEmpty($package->getAltura());
			$cond2 = !$validate::isNotEmpty($package->getComprimento());
			$cond3 = !$validate::isNotEmpty($package->getLargura());
			$cond4 = !$validate::isNotEmpty($package->getPeso());
			$cond5 = !$validate::isNotEmpty($package->getValor());
			if( $cond1 || $cond2 || $cond3 || $cond4 || $cond5 )
			{
				throw new \Exception($validate::ERROR_REQUIRED);
			}
			
			//params para enviar
			$params = array();
			$params['declaredValue'] = $this->aplicarRegrasParaValorDeclarado($package->getValor()); //definir regras de valor declarado do produto para caso de reembolso por extravio
			$params['weight'] = ($package->getPeso() < 1) ? 1 : $package->getPeso(); //peso bruto da encomenda em kg
			$params['height'] = $package->getAltura(); //altura da encomenda
			$params['width'] = $package->getLargura(); //largura da encomenda
			$params['length'] = $package->getComprimento(); //comprimento da encomenda
			
			//faz a consulta na api
			$url = "postalcodes/" . $recipient->getCep() . "/rates";
			$response = $this->api->call($url, $params, 'POST')->result();
			
			//erros
			if( empty($response->shippingServices) )
			{
				throw new \Exception($validate::ERROR_CEP_AREA);
			}
			
			//resultado
			$shipmentMultiple = new ShipmentMultiple();
			
			//resultado item
			foreach( $response->shippingServices as $row )
			{
				$servico = mb_strtolower($row->name);
				$servico = (strpos($servico, 'super rápido') !== false) ? self::ENTREGA_SUPERRAPIDO : ((strpos($servico, 'rápido') !== false) ? self::ENTREGA_RAPIDO : self::ENTREGA_ECONOMICO);
				
				//arredondar preço para cima em 1 casa decimal
				$row->price = ceil($row->price * 10) / 10;
				
				$item = new Shipment();
				$item->setServico($servico);
				$item->setPrazo($row->days);
				$item->setValor($row->price);
				$shipmentMultiple->addItem($item);
			}
			
			return $shipmentMultiple;
			
		} catch( \Exception $e ){
			
			throw new \Exception($e->getMessage(), $e->getCode());
			
		}
	}

    public function solicitarEtiqueta(Order $order)
    {
        try
        {
            //definir código de rastreio
            $codigo_rastreio = "NAICH" . date('ymdhis') . chr(rand(65,90)) . chr(rand(65,90));
            
            //consultar API
            $params = $this->getParamsSolicitarEtiqueta($order, $codigo_rastreio);
            $url = "orders/add-parcel";
            $response = $this->api->call($url, $params, 'POST')->result();
            
            //validar
            if( empty($response->id) )
            {
                throw new \Exception('Houve um problema ao solicitar a etiqueta!');
            }
            
            //definir shipmentEtiqueta para retornar
            $shipmentEtiqueta = new ShipmentEtiqueta();
            $shipmentEtiqueta->setId($response->id);
            $shipmentEtiqueta->setApi(self::NAME);
            $shipmentEtiqueta->setTipo($this->getServicoDePostagem($order->getServico()));
            $shipmentEtiqueta->setValor($order->getFreteTotal());
            $shipmentEtiqueta->setPrazo($order->getItemByPosition(0)->getFretePrazo());
            $shipmentEtiqueta->setRastreio($codigo_rastreio);
            $shipmentEtiqueta->setNfe($order->getItemByPosition(0)->getNfeChave());
            $shipmentEtiqueta->setDataCriado($response->processingDate);
            $shipmentEtiqueta->setDataColeta($response->pickupDate);
            return $shipmentEtiqueta;
            
        } catch( \Exception $e ){
        
            //registrar log de erro
            $var = array(
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'order' => $order->toArray(),
                'response' => $response,
            );
            \Naicheframework\Log\Log::error($e->getMessage(), $var);
        
            //criar exceção
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function imprimirEtiqueta(Order $order)
    {
        try 
        {
            //corrigir tipo de serviço
            $servico = $this->getServicoDePostagem($order->getServico());
            $order->setServico($servico);
            
            //exibir PDF
            $html = $this->render('painel/pedido/partials/etiqueta', ['order'=>$order]);
            $pdf = new \Naicheframework\Pdf\Pdf();
            return $pdf->html2pdf($html);
        
        } catch( \Exception $e ){
        
            //registrar log de erro
            $var = array(
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'order' => $order->toArray(),
            );
            \Naicheframework\Log\Log::error($e->getMessage(), $var);
        
            //criar exceção
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function rastrearPedido($code)
    {
        try
        {
            //consultar API
            $url = "trackings/" . $code;
            $this->api->setPostFields(false);
            $response = $this->api->call($url, [], 'GET')->result();
            
            //validar código
            if( empty($response->trackingCode) )
            {
                throw new \Exception('Nenhum código válido foi informado para rastrear');
            }
            
            //definir ShipmentTrack
            $track = new \Naicheframework\Shopping\Model\ShipmentTrack();
            $track->setCodigo($response->trackingCode);
            
            //loop nos eventos
            foreach( $response->events as $row )
            {
                $event = new \Naicheframework\Shopping\Model\ShipmentTrackEvent();
                $event->setDescricao($row->description);
                $event->setData($row->date);
                $event->setHoraByDatetime($row->date);
                $event->setStatusByMandae($row->name);
                $track->addEvento($event);
            }
        	
            return $track;
        	
        } catch( \Exception $e ){
        
            throw new \Exception($e->getMessage(), $e->getCode());
        
        }
    }

    public function getLinkRastreio($code = null)
    {
        return "https://app.mandae.com.br/rastreamento/" . $code;
    }

    /**
     * aplicar regras para definir o valor que será declarado no seguro da entrega
     * @param number $declaredValue
     * @return number
     */
    private function aplicarRegrasParaValorDeclarado($declaredValue)
    {
        //aplicar regras
        if( $declaredValue <= 100 )
        {
            //até 100 declarar 0
            //$declaredValue = 0;
             
        } elseif( $declaredValue <= 399 ){
             
            //de 101 até 399 declarar 10%
            //$declaredValue = $declaredValue * 10 / 100;
        }
    
        return $declaredValue;
    }
    
    private function getParamsSolicitarEtiqueta(Order $order, $codigo_rastreio)
    {
        $sender = array(
            "fullName" => $order->getRemetente()->getNome(),
            "address" => [
                "postalCode" => $order->getRemetente()->getCep(),
                "street" => $order->getRemetente()->getLogradouro(),
                "number" => $order->getRemetente()->getNumero(),
                "neighborhood" => $order->getRemetente()->getBairro(),
                "city" => $order->getRemetente()->getCidade(),
                "state" => $order->getRemetente()->getEstado(),
                "country" => "BR"
            ]
        );
        
        $recipient = array(
            "fullName" => $order->getDestinatario()->getNome(),
            "phone" => $order->getDestinatario()->getTelefone(true),
            "email" => $order->getDestinatario()->getEmail(),
            "document" => $order->getDestinatario()->getDocumento(true),
            "address" => [
                "postalCode" => $order->getDestinatario()->getCep(),
                "street" => $order->getDestinatario()->getLogradouro(),
                "number" => $order->getDestinatario()->getNumero(),
                "neighborhood" => $order->getDestinatario()->getBairro(),
                "city" => $order->getDestinatario()->getCidade(),
                "state" => $order->getDestinatario()->getEstado(),
                "country" => "BR",
                "reference" => $order->getDestinatario()->getReferencia(),
            ]
        );
        
        $package =  $order->getPackageCurrent();
        $dimensions = array(
            "height" => $package->getAltura(), //Altura (cm)
            "width" => $package->getLargura(), //Largura (cm)
            "length" => $package->getComprimento(), //Comprimento (cm)
            "weight" => $package->getPeso(), //Peso bruto (kg)
        );
        
        $items = array(
        
            //Nome e endereço do destinatário
            "recipient" => $recipient,
        
            //Serviço de envio. Valores válidos: Rapido, Economico
            "shippingService" => $this->getServicoDePostagem($order->getServico()),
        
            //Serviços de envio adicionais
            "valueAddedServices" => [
                array(
                    "name" => "ValorDeclarado",
                    "value" => $this->aplicarRegrasParaValorDeclarado($order->getTotal()),
                ),
            ],
        
            //Observações sobre o item (ex. item frágil)
            //"observation" => "Item frágil",
        
            //Caso o lojista tenha necessidade de um método para separar e identificar seus produtos para a coleta da Mandaê, fornecemos gratuitamente uma série de adesivos com códigos QR CODES, já impressos. Ao utilizar o QR CODE, o lojista ficará responsável por informar manualmente ao sistema qual o código inserido em cada venda realizada. Exemplo de código QR CODE: AAA010
            //"qrCodes" => ["AAA098","AAA099"],
        
            //Seu identificador do item. Depois que o item é embalado e enviado, nós iremos enviar esse identificado no webhook de item processado para que você possa atualizar os dados de envio do item
            "partnerItemId" => $order->getIdPedido(),
        
            //Informações dos SKUs
            "skus" => array(),
        
            //Informações da Nota fiscal
            "invoice" => [
                "id" => $order->getItemByPosition(0)->getNfeNumero(), //Número da nota fiscal
                "key" => $order->getItemByPosition(0)->getNfeChave(), //Chave de acesso da nota fiscal
            ],
        
            //Código de rastreio com prefixo NAICH
            "trackingId" => $codigo_rastreio,
        
            //Dimensões da embalagem
            "dimensions" => $dimensions,
        
            //Canal de venda (direct ou ecommerce)
            "channel" => "ecommerce",
        
            //Nome da loja que realizou a venda
            "store" => "Octoplace",
        
            //Valor total da Nota Fiscal
            "totalValue" => $order->getTotal(),
        
            //Valor total do frete
            "totalFreight" => $order->getFreteTotal(),
        );
        
        //loop nos itens
        foreach( $order->getItem() as $item )
        {
            $freight = !empty($item->getFreteValor()) ? $item->getFreteValor() : ($order->getTotal() / $order->countItem());
            
            //add SKUs
            $items["skus"][] = array(
                "skuId" => $item->getId(), //Identificador
                "description" => $item->getDescricao(), //Descrição
                "ean" => $item->getEan(), //EAN
                "price" => $item->getPreco(), //Preço
                "freight" => $freight, //Preço do frete
                "quantity" => $item->getQuantidade() //Quantidade
            );
        }
        
        $params = array(
            "customerId" => $this->customer_id, //Identificador do cliente
            "scheduling" => date('c', strtotime(date('Y-m-d 10:00:00', strtotime('+1 day')))), //Horário do agendamento (formato ISO-8601)
            "items" => array($items), //Cada venda realizada, que pode conter um ou mais produtos para o mesmo destinatário
            "sender" => $sender, //Endereço de retirada
            "vehicle" => "Car", //Veículo usado na retirada. Valores válidos: Car, Motorcyle, Dropoff
            //"label" => null, //Objeto Sender - Caso você queira que o endereço impresso na etiqueta de envio seja diferente do endereço de retirada, envie aqui os dados do endereço da etiqueta
            //"observation" => null, //Observações sobre a coleta (ex. itens frágeis)
            //"partnerOrderId" => null, //Seu identificador da coleta. Quando for usado o serviço assíncrono de criação de coleta, esse identificador será enviado pelo webhook para que você possa confirmar a criação da coleta
        );
        //echo'<pre>'; print_r($params); exit;
        
        return $params;
    }
    
    private function getServicoDePostagem($servico)
    {
        //definir serviço de envio
        $cond1 = (strpos($servico, 'SEDEX') !== false) ? true : false;
        $cond2 = (strpos($servico, 'Rápido') !== false) ? true : false;
        return ($cond1 || $cond2) ? 'Rapido' : 'Economico';
    }
    
    private function render($layout, $vars=array())
    {
        $resolver = new \Zend\View\Resolver\TemplateMapResolver();
        $resolver->add('template', __DIR__ . '/../../../../module/Painel/view/' . $layout . '.phtml');
         
        $view = new \Zend\View\Renderer\PhpRenderer();
        $view->setResolver($resolver);
         
        $viewLayout = new \Zend\View\Model\ViewModel();
        $viewLayout->setTemplate('template');
         
        if( !empty($vars) )
        {
            foreach( $vars as $key=>$value )
            {
                $viewLayout->setVariable($key, $value);
            }
        }
         
        return $view->render($viewLayout);
    }
}