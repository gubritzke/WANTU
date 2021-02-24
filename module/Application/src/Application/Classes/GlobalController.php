<?php
namespace Application\Classes;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

/**
 * @NAICHE - Deco
 * Esta classe possui funcoes que encurtam as funcoes padroes do ZF2
 */
class GlobalController extends AbstractActionController
{
	/**
	 * Tables
	 *
	 * @var object
	 */
	protected $tb = null;
	protected $api = null;

	/**
	 * Database adapter
	 *
	 * @var \Zend\Db\Zend\Db\Adapter\AdapterInterface
	 */
	protected $adapter = null;

	/**
	 * set vars to view in controller
	 * @var array
	 */
	protected $view = array();

	/**
	 * @var 
	 */
	protected $head = null;
	
	/**
	 * @var
	 */
	protected $sms = null;
	
	/**
	 * requestApi instance
	 * @var \Naicheframework\RequestApi\HttpPost
	 */
	protected $requestApi = null;

	/**
	 * Estende o metodo para definir os parametros globais da class
	 *
	 * @return void
	 */
	protected function attachDefaultListeners()
	{
		parent::attachDefaultListeners();
		
		$this->tb = $this->getServiceLocator()->get('tb');
		$this->adapter = $this->getServiceLocator()->get('db');
		
		$this->head = new \Naicheframework\Head\Head(
			$this->getServiceLocator(), 
			'Application' // MODULE
		);
		
	}

	public function __construct()
	{
		
	}
	
	/**
	 * habilita acesso externo
	 */
	public function headerAccessControl()
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Credentials: true");
	    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, HEADER');
	    header('Access-Control-Max-Age: 1000');
	    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
	    
	}
	
	/**
	 * recupera o post
	 * @return array
	 */
	public function getPost()
	{
	    //Receive the RAW post data.
	    $content = trim(file_get_contents("php://input"));
	    
	    //Attempt to decode the incoming RAW post data from JSON.
	    $decoded = json_decode($content, true);
	    
	    return $decoded;
	}
	
	public function onDispatch(MvcEvent $e)
	{
	    $this->api = new \Naicheframework\Api\Request('http://10.0.0.224:8325/v1', null);
	    
	    if(!empty($this->layout()->me->usuario->id_usuario)){
	        $this->layout()->saldoAtual = $this->setSaldo();
	    }

		$this->checkPermissions();
		//$this->forceHTTPS();
		$this->globalVariables();
		
		
		//call parent method
		return parent::onDispatch($e);
		
	}
	
	protected function setSaldo(){
	    $saldo = array();
	    
	    $usuario = new ModelUsuario($this->tb, $this->adapter);
	    
	    $saldo['validacao'] = $usuario->getSaldoValidacoes($this->layout()->me->usuario->id_usuario, $this->layout()->me->saldo->validacao);
	    $saldo['complemento'] = $usuario->getSaldoComplementos($this->layout()->me->usuario->id_usuario, $this->layout()->me->saldo->completar);
	    $saldo['campanhas'] = $usuario->getSaldoCampanhas($this->api, $this->layout()->me->usuario->id_usuario, $this->layout()->me->saldo->campanhas);
	    $saldo['comportamento'] = $usuario->getSaldoComportamento($this->layout()->me->usuario->id_usuario, $this->layout()->me->saldo->comportamento);
	    
	    return $saldo;
	}
	
	private function globalVariables()
	{
		$global = array();
		
		
		
		$this->layout()->setVariable('global', $global);
		//\Naicheframework\Session\Session::set('global', $global);
	}
	
	/**
	 * força o protocolo HTTPS nas páginas específicas
	 */
	private function forceHTTPS()
	{
		//define as rotas
		$routes = $this->layout()->routes;
		
		//define o ambiente
		$environment = $this->layout()->config_host['env'];
		
		//define a url atual
		$url = $_SERVER['REQUEST_URI'];
		
		//define o protocolo atual
		$protocolCurrent = ($_SERVER['HTTPS'] == true) ? 'https://' : 'http://';
		
		
		//protocolo do site
		$cond1 = ($routes['module'] == 'application') && in_array($routes['controller'], ['login','cadastro','conta']);
		$cond2 = ($routes['module'] == 'painel');
		//$cond2 = ($routes['module'] == 'gerenciador');
		if( $cond1 || $cond2 )
		{
			$protocolChange = 'http://';
			
		} else {
			$protocolChange = 'http://';
		}
		
		//redirecionamento
		$cond1 = ($environment != 'local');
		$cond2 = ($protocolCurrent != $protocolChange);
		if( $cond1 && $cond2 )
		{
			$redirect = $protocolChange . $_SERVER['HTTP_HOST'] . $url;
			return $this->redirect()->toUrl($redirect);
		}
	}
	
	/**
	 * faz o controle de acesso nas áreas restritas
	 */
	private function checkPermissions()
	{
		//define as rotas
		$routes = $this->layout()->routes;
		
		//define o usuário logado
		$me = $this->layout()->me;
		
		//define a url atual
		$url = $_SERVER['REQUEST_URI'];
		
		try 
		{
			
			//acesso restrito ao painel
			if( ($routes['module'] == 'painel') && (empty($me->email) || $me->nivel!=1) )
			{
				//echo'<pre>'; print_r($routes['module']); exit;
				//restrito para todos os controllers, exceto o login
				if( $routes['controller'] != 'login' )
				{
					throw new \Exception(false);
				}
			}
		
			//acesso restrito ao site
			if( ($routes['module'] == 'application') && (empty($me->email) || $me->nivel!=0) )
			{
				//restrito para todos os controllers
				if( in_array($routes['controller'], ['minha-conta', 'perfis-comportamentais', 'analise', 'aptidoes-profissionais', 'competencias', 'pontos-fortes', 'inteligencias-multiplas', 'checkout']) )
				{
					throw new \Exception(false);
				}
			}
			
			return true;
			
		} catch( \Exception $e ){
			
			//mensagem de erro
			if( $e->getMessage() != false )
			{
				$this->flashMessenger()->addErrorMessage($e->getMessage());
			}
			
		     //redirecionamento
		     $redirect = ($routes['module'] == 'painel') ? '/painel' : null;
		     $redirect .= '/login?r=' . $url;
			
			//echo '<pre>'; print_r($redirect); exit;
			return $this->redirect()->toUrl($redirect);
			
		}
	}
	
}