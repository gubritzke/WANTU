<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
	public function onBootstrap(MvcEvent $e)
	{
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
		
		/*
		 * @NAICHE | Leandro
		 * PEGA AS CONFIGURACOES DO CONFIG E SETA NO INI_SET
		 */
		$config = $e->getApplication()->getServiceManager()->get('config');
		$phpSettings = $config['phpSettings'];
		if( $phpSettings ) {
			foreach($phpSettings as $key => $value)
				ini_set($key, $value);
		}
		
		// Register a render event
		$app = $e->getParam('application');
		$app->getEventManager()->attach('render', array($this, 'setLayoutTitle'));
		
		/*
		 * @NAICHE - Leandro
		 * VARIAVEIS PARA VIEW
		 */
		$viewModel = $e->getViewModel();
		$viewModel->setVariable('config_host', $config['config_host']);
		
		/*
		 * @NAICHE - Leandro
		 */
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
				$this,
				'beforeDispatch'
		), 100);
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
				$this,
				'afterDispatch'
		), -100);
	}
	
	public function beforeDispatch(MvcEvent $e)
	{
		//definir variáveis globais
		$viewModel = $e->getViewModel();
		
		//definir as configurações
		$config = $e->getApplication()->getServiceManager()->get('config');
		
		//definir configurações do PHP para o ini_set
		$phpSettings = $config['phpSettings'];
		if( $phpSettings )
		{
			foreach( $phpSettings as $key => $value ) ini_set($key, $value);
		}
		
		//definir rotas nas variáveis globais
		$matches 	= $e->getRouteMatch();
		$module		= $matches->getParam('__NAMESPACE__');
		$controller	= $matches->getParam('__CONTROLLER__');
		$action		= $matches->getParam('action');
		
		$routes = array();
		$routes['module']		= strtolower(substr($module, 0, strpos($module, '\\')));
		$routes['controller']	= strtolower($controller);
		$routes['action']		= strtolower($action);
		$viewModel->setVariable('routes', $routes);
		
		//definir configurações nas variáveis globais
		$viewModel->setVariable('config_host', $config['config_host']);
		$viewModel->setVariable('config_payment', $config['config_payment']);
		$viewModel->setVariable('config_smtp', $config['config_smtp']);
		
		/*
		 * @NAICHE - Deco
		 * verifica se a sessão do login está ativa
		 */
		if( $routes['module'] == "application" )
		{
			$session = new \Zend\Session\Container('Auth');
			if( $session->offsetExists('me') )
			{
				$viewModel->setVariable('me', $session->offsetGet('me'));
			}
		} else if( $routes['module'] == "painel" )
		{
			$session = new \Zend\Session\Container('Auth2');
			if( $session->offsetExists('me') )
			{
				$viewModel->setVariable('me', $session->offsetGet('me'));
			}
		}
		
		/*
		 * @NAICHE - Deco
		 * inicializa os logs
		 */
		$this->logsInit($viewModel);
	}
	
	/**
	 * called after any controller action called. Do any operation.
	 * @NAICHE - Leandro
	 */
	public function afterDispatch(MvcEvent $e)
	{
	}
	
	/**
	 * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
	 * @return void
	 */
	public function setLayoutTitle($e)
	{
		//get service manager
		$sm = $e->getApplication()->getServiceManager();
		
		//get view model
		$viewModel = $e->getViewModel();
		
		//get view helper manager from the application service manager
		$viewHelperManager = $sm->get('viewHelperManager');
		
		//to view
		$viewModel->setVariable('headTitle', $viewHelperManager->get('headTitle'));
	}
	
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\StandardAutoloader' => array(
						'namespaces' => array(
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
								'Naicheframework' => __DIR__ . '/../../vendor/naicheframework',
						),
				),
		);
	}
	
	/*
	 * @NAICHE | Deco
	 * helpers instances
	 */
	public function getViewHelperConfig()
	{
		return array(
				'factories' => array(
						'message' => function($sm) {
						return new View\Helper\Message($sm->getServiceLocator()->get('ControllerPluginManager')->get('flashmessenger'));
						},
						
						// 				'categories' => function($sm){
						// 					return new \Application\View\Helper\Categories($sm->getServiceLocator());
						// 				}
		)
		);
	}
	
	public function getServiceConfig()
	{
		return array(
				'factories' => array(
						
						'tb' => function ($sm) {
						$tabelas = $sm->get('config');
						return (object) $tabelas['tb'];
						},
						
						)
				);
	}
	
	/**
	 * log registrado sempre que uma página é carregada
	 */
	private function logsInit($viewModel)
	{
		//define as rotas
		$routes = $viewModel->getVariable('routes');
		
		//define o usuário
		$me = $viewModel->getVariable('me');
		
		//define o login
		$login = (!empty($me->login) ? $me->login : false);
		
		//caso existir, registra o usuário logado
		\Naicheframework\Log\Log::setUser($login);
		
		//registra o log
		\Naicheframework\Log\Log::debug('acesso', array(
				'module' => $routes['module'],
				'controller' => $routes['controller'],
				'action' => $routes['action'],
				'get' => $_GET,
				'post' => $_POST,
		));
	}
}