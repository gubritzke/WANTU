<?php
namespace Naicheframework\Shopping\Shipment;
use Naicheframework\Shopping\Model\Shipment;
use Naicheframework\Shopping\Model\Sender;
use Naicheframework\Shopping\Model\Package;
use Naicheframework\Shopping\Model\Recipient;
use Naicheframework\Shopping\Validate\ValidateCalcularFrete;
use Naicheframework\Shopping\Model\ShipmentMultiple;
use Naicheframework\Shopping\Model\Order;
use PhpSigep\Model\PreListaDePostagem;
use Naicheframework\Shopping\Model\ShipmentEtiqueta;

require_once(dirname(__FILE__) . '/library/PhpSigepFPDF/PhpSigepFPDF.php');
require_once(dirname(__FILE__) . '/library/autoload.php');

/**
 * @NAICHE | Vitor Deco
 */
class ShipmentCorreio extends ShipmentAbstract
{
	//método de envio
	const TYPE = "CORREIO";
	
	//nome do método de envio
	const NAME = "Correios";
	
	//user e password do portal
	const ADM_USERNAME = "alana@naiche.com.br";
	const ADM_PASSWORD = "a4Y45";
	
	//user e password de teste
	const SANDBOX_USERNAME = "ECT";
	const SANDBOX_PASSWORD = "SRO";
    
	//definir tipos de entrega
	const ENTREGA_SUPERRAPIDO = "Correios SEDEX 10";
	const ENTREGA_RAPIDO = "Correios SEDEX";
	const ENTREGA_ECONOMICO = "Correios PAC";
	
	//user e password para consultas na API
	protected $username = null;
	protected $password = null;
	
	//user e password para rastrear pedidos
	protected $rastreio_username = null;
	protected $rastreio_password = null;
	protected $rastreio_codigo_administrativo = null;
	
	//mais informações de acesso
	//http://www.corporativo.correios.com.br/encomendas/sigepweb
	//http://www2.correios.com.br/encomendas/servicosonline
	protected $cnpj = null;
	protected $codigo_administrativo = null;
	protected $cartao_postagem = null;
	protected $numero_contrato = null;
	
	/**
	 * dados de acesso
	 * @var \PhpSigep\Model\AccessData
	 */
	protected $accessData = null;
	
    public function __construct(array $config)
	{
	    //definir configurações
	    foreach( $config as $key => $value )
	    {
	        $this->$key = $value;
	    }
	    
		//tipo de acesso
		$this->accessData = new \PhpSigep\Model\AccessData();
		$this->accessData->setUsuario($this->username);
		$this->accessData->setSenha($this->password);
		$this->accessData->setCnpjEmpresa($this->cnpj);
		$this->accessData->setCodAdministrativo($this->codigo_administrativo);
		$this->accessData->setCartaoPostagem($this->cartao_postagem);
		$this->accessData->setNumeroContrato($this->numero_contrato);

		$diretoria = new \PhpSigep\Model\Diretoria(\PhpSigep\Model\Diretoria::DIRETORIA_DR_SAO_PAULO);
		$this->accessData->setDiretoria($diretoria);
		
		//define as configurações
		$config = new \PhpSigep\Config();
		$config->setAccessData($this->accessData);
		
		//define o ambiente
		$config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
		
		//define opções de cache
		$config->setCacheOptions(
		    array(
		        'storageOptions' => array(
		            'enabled' => true,
		            'ttl' => 60*60*24*7, //"time to live" de 10 segundos
		        ),
		    )
		);
		
		//inicializar a API PhpSigep
		\PhpSigep\Bootstrap::start($config);
		
		//define o método de envio
		parent::__construct(self::TYPE, self::NAME);
	}
	
	public function setSandbox($bool)
	{
		if( $bool === true )
		{
			//tipo de acesso
			$this->accessData = new \PhpSigep\Model\AccessDataHomologacao();
			
			//define as configurações
			$config = new \PhpSigep\Config();
			$config->setAccessData($this->accessData);
			
			//define o ambiente
			$config->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT);
			
			//inicializar a API PhpSigep
			\PhpSigep\Bootstrap::start($config);
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
			if( $cond1 || $cond2 || $cond3 || $cond4 )
			{
				throw new \Exception($validate::ERROR_REQUIRED);
			}
			
			//define o tipo do pacote padrão caso não tenha sido informado
			if( empty($package->getTipo()) )
			{
				$package->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);
			}
			
			//serviços para consultar
			$pac = new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA);
			$sedex = new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA);
			$sedex10 = new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_10);
			$servicosPostagem = array($pac, $sedex, $sedex10);
			
			//definir dimensao
			$dimensao = new \PhpSigep\Model\Dimensao();
			$dimensao->setAltura($package->getAltura());
			$dimensao->setComprimento($package->getComprimento());
			$dimensao->setLargura($package->getLargura());
			$dimensao->setTipo($package->getTipo());
			
			//definir parâmetros para a consulta
			$params = new \PhpSigep\Model\CalcPrecoPrazo();
			$params->setAccessData($this->accessData);
			$params->setCepOrigem($sender->getCep());
			$params->setCepDestino($recipient->getCep());
			$params->setServicosPostagem($servicosPostagem);
			$params->setAjustarDimensaoMinima(true);
			$params->setDimensao($dimensao);
			$params->setPeso($package->getPeso());

			//faz a consulta na api
			$phpSigep = new \PhpSigep\Services\SoapClient\Real();
			$response = $phpSigep->calcPrecoPrazo($params)->getResult();
			//echo'<pre>'; print_r($response); exit;
			
			//resultado
			$shipmentMultiple = new ShipmentMultiple();
			foreach( $response as $row )
			{
				//se não retornou valor continua
				if( $row->getValor() <= 0 ) continue;
				
				//define o nome do serviço (Pac, Sedex, Sedex 10)
				$servico = mb_strtolower($row->getServico()->getNome());
				$servico = (strpos($servico, 'sedex 10') !== false) ? self::ENTREGA_SUPERRAPIDO : ((strpos($servico, 'sedex') !== false) ? self::ENTREGA_RAPIDO : self::ENTREGA_ECONOMICO);

				//[MDC] case CEP for Alvorada
				if( $sender->getCep() == "07030140" )
				{
				    //add 10% no valor do frete
				    $valor = $row->getValor() + ($row->getValor() * 10 / 100);
				    
				} else {
				    
				    //add 5% no valor do frete
				    $valor = $row->getValor() + ($row->getValor() * 5 / 100);
				}
				
				//resultado item
				$item = new Shipment();
				$item->setServico($servico);
				$item->setPrazo($row->getPrazoEntrega());
				$item->setValor($valor);
				$item->setObservacao($row->getErroMsg());
				$shipmentMultiple->addItem($item);
			}
		    
			//retorno
			return $shipmentMultiple;
		
		} catch( \Exception $e ){
				
			throw new \Exception($e->getMessage(), $e->getCode());
				
		}
	}
	
	public function solicitarEtiqueta(Order $order)
	{
		try 
		{
		    //definir serviço de postagem
		    $servicoDePostagem = $this->getServicoDePostagem($order);
		    
			//definir params para gerar etiquetas
			$params = new \PhpSigep\Model\SolicitaEtiquetas();
			$params->setQtdEtiquetas(1);
			$params->setServicoDePostagem($servicoDePostagem);
			$params->setAccessData($this->accessData);
			
			//gerar etiquetas com código de rastreio
			$phpSigep = new \PhpSigep\Services\SoapClient\Real();
			$etiquetaSolicitada = $phpSigep->solicitaEtiquetas($params);
			$etiquetas = $etiquetaSolicitada->getResult();
			
			//definir etiqueta para adicionar ao pacote
			$etiqueta = current($etiquetas);
			if( empty($etiqueta) )
			{
			    throw new \Exception('Houve um problema ao solicitar a etiqueta!');
			}
			
			//definir código de rastreio
			$codigo_rastreio = $etiqueta->getEtiquetaComDv();
			
			//adicionar código de rastreio ao pacote
			$order->getPackageCurrent()->setRastreio($codigo_rastreio);
			
			//definir params da PLP
			$plp = $this->paramsSolicitarEtiqueta($order);
			
			//enviar PLP para o sistema dos Correios
			$phpSigep = new \PhpSigep\Services\SoapClient\Real();
			$response = $phpSigep->fechaPlpVariosServicos($plp);
			if( $response->getResult() == null )
			{
			    throw new \Exception($response->getErrorMsg());
			}
			
			//definir shipmentEtiqueta para retornar
			$shipmentEtiqueta = new ShipmentEtiqueta();
			$shipmentEtiqueta->setId($order->getIdPedido());
			$shipmentEtiqueta->setPlp($response->getResult()->getIdPlp());
			$shipmentEtiqueta->setApi(self::NAME);
			$shipmentEtiqueta->setTipo($order->getServico());
			$shipmentEtiqueta->setValor($order->getFreteTotal());
			$shipmentEtiqueta->setPrazo($order->getItemByPosition(0)->getFretePrazo());
			$shipmentEtiqueta->setRastreio($codigo_rastreio);
			$shipmentEtiqueta->setNfe($order->getItemByPosition(0)->getNfeChave());
			$shipmentEtiqueta->setDataCriado(date('Y-m-d'));
			return $shipmentEtiqueta;
			
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
	
	public function imprimirEtiqueta(Order $order)
	{
		try 
		{
		    //definir params da PLP
		    $plp = $this->paramsSolicitarEtiqueta($order);
		    
		    //definir id PLP
		    $idPlp = $order->getPackageCurrent()->getShipmentEtiqueta()->getPlp();
		    
			//gerar PDF com as etiquetas
            $subdomain = array_shift((explode('.', $_SERVER['HTTP_HOST'])));
            $logo = 'http://img.' . $subdomain . '.octoplace.com.br/logos/etiqueta.png';
			$pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($plp, $idPlp, $logo);
			return $pdf->render("S", "etiqueta.pdf");
			
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
		    //validar código
		    if( strlen($code) != 13 )
		    {
		        throw new \Exception("Nenhum código válido foi informado para rastrear");
		    }
		    
		    //definir tipo de acesso
		    //correios tem um usuário específico para esse serviço
		    $accessData = new \PhpSigep\Model\AccessData();
		    $accessData->setUsuario($this->rastreio_username);
		    $accessData->setSenha($this->rastreio_password);
		    $accessData->setCodAdministrativo($this->rastreio_codigo_administrativo);
		    
			//params para rastrear
			$params = new \PhpSigep\Model\RastrearObjeto();
			$params->setAccessData($accessData);
			$params->setExibirErros(true);
			
		    //definir etiqueta
		    $etiqueta = new \PhpSigep\Model\Etiqueta();
		    $etiqueta->setEtiquetaComDv($code);
		    $params->addEtiqueta($etiqueta);
			
			//rastrear
			$phpSigep = new \PhpSigep\Services\SoapClient\Real();
			$rastrearObjetoResultadoArray = $phpSigep->rastrearObjeto($params)->getResult();
			//echo'<pre>'; print_r($rastrearObjetoResultadoArray); exit;
            
			//apenas um resultado
			$rastrearObjetoResultado = current($rastrearObjetoResultadoArray);
            
		    //definir ShipmentTrack
		    $track = new \Naicheframework\Shopping\Model\ShipmentTrack();
		    $track->setCodigo($rastrearObjetoResultado->getEtiqueta()->getEtiquetaComDv());
		    
		    //loop nos eventos
		    foreach( $rastrearObjetoResultado->getEventos() as $rastrearObjetoEvento )
		    {
		        $event = new \Naicheframework\Shopping\Model\ShipmentTrackEvent();
		        $event->setDescricao($rastrearObjetoEvento->getDescricao());
		        $event->setData($rastrearObjetoEvento->getDataHora());
		        $event->setHoraByDatetime($rastrearObjetoEvento->getDataHora());
		        $event->setLocal($rastrearObjetoEvento->getLocal());
		        $event->setCidade($rastrearObjetoEvento->getCidade());
		        $event->setEstado($rastrearObjetoEvento->getUf());
		        $event->setStatusByCorreios($rastrearObjetoEvento->getDescricao());
		        $track->addEvento($event);
		    }
			
			return $track;
			
		} catch( \Exception $e ){
		
			throw new \Exception($e->getMessage(), $e->getCode());
		
		}
	}
	
	public function getLinkRastreio($code = null)
	{
	    return "http://www2.correios.com.br/sistemas/rastreamento/default.cfm";
	}
	
	/**
	 * @param Order $order
	 * @return PreListaDePostagem
	 */
	private function paramsSolicitarEtiqueta(Order $order)
	{
		//PLP
		$plp = new \PhpSigep\Model\PreListaDePostagem();
		$plp->setAccessData($this->accessData);
		
		//definir destinatario
		$destinatario = new \PhpSigep\Model\Destinatario();
		$destinatario->setNome($order->getDestinatario()->getNome());
		$destinatario->setLogradouro($order->getDestinatario()->getLogradouro());
		$destinatario->setNumero($order->getDestinatario()->getNumero());
		$destinatario->setComplemento($order->getDestinatario()->getComplemento());
		$destino = new \PhpSigep\Model\DestinoNacional();
		$destino->setCep($order->getDestinatario()->getCep());
		$destino->setBairro($order->getDestinatario()->getBairro());
		$destino->setCidade($order->getDestinatario()->getCidade());
		$destino->setUf($order->getDestinatario()->getEstado());
		
		//definir remetente
		$remetente = new \PhpSigep\Model\Remetente();
		$remetente->setNome($order->getRemetente()->getNome());
		$remetente->setCep($order->getRemetente()->getCep());
		$remetente->setLogradouro($order->getRemetente()->getLogradouro());
		$remetente->setNumero($order->getRemetente()->getNumero());
		$remetente->setComplemento($order->getRemetente()->getComplemento());
		$remetente->setBairro($order->getRemetente()->getBairro());
		$remetente->setCidade($order->getRemetente()->getCidade());
		$remetente->setUf($order->getRemetente()->getEstado());
		$plp->setRemetente($remetente);
		
		//loop nos pacotes
		$encomendas = array();
		foreach( $order->getPackage() as $package )
		{
		    //definir serviço de postagem
		    $servicoDePostagem = $this->getServicoDePostagem($order);
		    
		    //definir dimensao
		    $dimensao = new \PhpSigep\Model\Dimensao();
		    $dimensao->setAltura($package->getAltura());
		    $dimensao->setComprimento($package->getComprimento());
		    $dimensao->setLargura($package->getLargura());
		    $dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);
		    
			//definir etiqueta
			$etiqueta = new \PhpSigep\Model\Etiqueta();
			$etiqueta->setEtiquetaComDv($package->getRastreio());
			
			//serviço adicional
			$servicoAdicional = new \PhpSigep\Model\ServicoAdicional();
			$servicoAdicional->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);
			$servicoAdicional->setValorDeclarado($package->getValor()); //se não tiver valor declarado informar 0 (zero)
			
			//definir encomenda
			$peso = ($package->getPeso() < 1) ? 1 : $package->getPeso();
			$encomenda = new \PhpSigep\Model\ObjetoPostal();
			$encomenda->setServicosAdicionais(array($servicoAdicional));
			$encomenda->setDestinatario($destinatario);
			$encomenda->setDestino($destino);
			$encomenda->setDimensao($dimensao);
			$encomenda->setEtiqueta($etiqueta);
			$encomenda->setPeso($peso); //em gramas (0.5 = 500g)
			$encomenda->setServicoDePostagem($servicoDePostagem);
			$encomendas[] = $encomenda;
		}
		$plp->setEncomendas($encomendas);
		//echo'<pre>'; print_r($plp); exit;
		
		return $plp;
	}
	
	private function getServicoDePostagem(Order $order)
	{
	    //definir instancia do serviço
	    $pac = new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA);
	    $sedex = new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA);
	    $sedex10 = new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_10);
	    
	    //definir serviço de entrega
	    $servico = $order->getServico();
        
	    //verificar se algum serviço foi setado
        //verificar se o remetende e destinatário são de SP
        $cond1 = empty($servico);
        $cond2 = ($order->getRemetente()->getEstado() == "SP" && $order->getDestinatario()->getEstado() == "SP");
        if( $cond1 || $cond2 )
        {
            //definir como SEDEX o serviço de entrega
            $servico = self::ENTREGA_RAPIDO;
            $order->setServico($servico);
        }
	    
	    //retornar instancia do serviço
		$servico = (strpos($servico, self::ENTREGA_SUPERRAPIDO) !== false) ? $sedex10 : ((strpos($servico, self::ENTREGA_RAPIDO) !== false) ? $sedex : $pac);
		return $servico;
	}
	
}