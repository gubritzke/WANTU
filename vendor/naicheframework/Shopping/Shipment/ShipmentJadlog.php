<?php
namespace Naicheframework\Shopping\Shipment;
use Naicheframework\Shopping\Model\Package;
use Naicheframework\Shopping\Model\Sender;
use Naicheframework\Shopping\Model\Recipient;
use Naicheframework\Shopping\Model\ShipmentMultiple;
use Naicheframework\Shopping\Model\Shipment;
use Naicheframework\Shopping\Validate\ValidateCalcularFrete;
use Naicheframework\Shopping\Model\Order;
use Naicheframework\Api\Request;

/**
 * @NAICHE | Vitor Deco
 */
class ShipmentJadlog extends ShipmentAbstract
{
	//método de envio
	const TYPE = "JADLOG";
	
	//nome do método de envio
	const NAME = "JadLog";
	
	//definir tipos de entrega
	const ENTREGA_DEFAULT = "Transportadora JadLog";
	
	//cnpj
	protected $cnpj = null;
	
	//password
	protected $password = null;
	
	/**
	 * class que faz o request na api
	 * @var Request
	 */
	protected $api;
	
	/**
	 * class para consultar o banco de dados
	 * @var Request
	 */
	protected $database;
	
	public function __construct(array $config)
	{
	    //definir configurações
	    foreach( $config as $key => $value )
	    {
	        $this->$key = $value;
	    }
	    
	    //instancia da class que faz o request na api
	    $url = "http://jadlog.com.br/JadlogEdiWs/services";
	    $this->api = new \Naicheframework\Api\Request($url);
	    $this->api->setEncoding(null);
	    $this->api->setAccept(null);
	    $this->api->setPostFields(false);
	    $this->api->setResultInArray(false);
	    
		//define o método de envio
		parent::__construct(self::TYPE, self::NAME);
	}
	
	public function getDatabase()
	{
	    return $this->database;
	}
	
	public function setDatabase(\Naicheframework\Api\Request $value)
	{
	    $this->database = $value;
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
            //$package->getAltura(); //altura da encomenda
            //$package->getLargura(); //largura da encomenda
            //$package->getComprimento(); //comprimento da encomenda
            $params = array();
            $params['method'] = "valorar";
            $params['vModalidade'] = 3; //Modalidade do frete. Deve conter apenas números
            $params['Password'] = $this->password;
            $params['vSeguro'] = "N"; //Tipo do Seguro / N - Normal (Mais Utilizado) / A - Apólice Própria
            $params['vVlDec'] = $package->getValor(); //Valor da Nota fiscal Ex: 100,00
            $params['vVlColeta'] = 5; //Valor da coleta negociado com a unidade JADLOG. Ex. 10,00 (Normalmente não é utilizado)
            $params['vCepOrig'] = $sender->getCep();
            $params['vCepDest'] = $recipient->getCep();
            $params['vPeso'] = ($package->getPeso() < 1) ? 1 : $package->getPeso(); //peso bruto da encomenda em kg
            $params['vFrap'] = "N"; //Frete a pagar no destino / S - Sim / N - Não
            $params['vEntrega'] = "D"; //Tipo de entrega / R - Retira Unidade JADLOG / D - Domicilio
            $params['vCnpj'] = $this->cnpj;
            
            //faz a consulta na api
            $url = "ValorFreteBean";
            $response = $this->api->call($url, $params, 'GET')->result();
            //echo"<pre>"; print_r($response); exit;
            
            //converter XML para SimpleXMLElement
        	$response = str_replace('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><valorarResponse xmlns=""><ns1:valorarReturn xmlns:ns1="http://jadlogEdiws">', '', $response);
        	$response = str_replace('</ns1:valorarReturn></valorarResponse></soapenv:Body></soapenv:Envelope>', '', $response);
        	$response = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $response);
        	$response = str_replace('&gt;', '>', $response);
        	$response = str_replace('&lt;', '<', $response);
        	$response = str_replace('&quot;', '"', $response);
        	$response = str_replace('<string xmlns="http://www.jadlog.com.br/JadlogWebService/services">', '', $response);
        	$response = str_replace('<string xmlns="http://www.jadlog.com.br/JadlogEdiWs/services">', '', $response);
        	$response = str_replace('</string>', '', $response);		
        	$response = simplexml_load_string($response);
        	//echo"<pre>"; print_r($response); exit;
        	
			//erros
			if( empty($response->Retorno) || $response->Retorno == "-1" )
			{
				throw new \Exception($validate::ERROR_CEP_AREA);
			}
			
			//definir prazo
			$where = "frete_calcular.cep_inicial <= '" . (int)$recipient->getCep() . "'";
			$where .= " AND frete_calcular.cep_final >= '" . (int)$recipient->getCep() . "'";
			$result = $this->database->call('frete_calcular/select', ['where'=>$where, 'limit'=>1], 'GET')->current();
			$prazo = !empty($result->prazo) ? $result->prazo : 15;
			
			//formatar valor
			$valor = str_replace(['.',','], ['','.'], $response->Retorno);
			
			//resultado
			$item = new Shipment();
			$item->setServico(self::ENTREGA_DEFAULT);
			$item->setPrazo($prazo);
			$item->setValor($valor);
			$shipmentMultiple = new ShipmentMultiple();
			$shipmentMultiple->addItem($item);
			
			return $shipmentMultiple;
			
		} catch( \Exception $e ){
			
			throw new \Exception($e->getMessage(), $e->getCode());
			
		}
	}
	
    /**
     * {@inheritDoc}
     * @see \Naicheframework\Shopping\Shipment\ShipmentAbstract::solicitarEtiqueta()
     */
    public function solicitarEtiqueta(\Naicheframework\Shopping\Model\Order $order)
    {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \Naicheframework\Shopping\Shipment\ShipmentAbstract::imprimirEtiqueta()
     */
    public function imprimirEtiqueta(\Naicheframework\Shopping\Model\Order $order)
    {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \Naicheframework\Shopping\Shipment\ShipmentAbstract::rastrearPedido()
     */
    public function rastrearPedido($code)
    {
        // TODO Auto-generated method stub
        
    }

    public function getLinkRastreio($code = null)
    {
        return "https://app.mandae.com.br/rastreamento/" . $code;
    }


}