<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        	
        	//DEFAULT
            'application' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '[]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/:id][/]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            	'id'         => '[0-9]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            		
            
            ),
        		
        		
        	//BLOG	
        	'blog' => array(
        			'type' => 'Segment',
        			'options' => array(
        					'route'    => '/blog/[:slug][/]',
        					'constraints' => array(
        							'slug' => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        							'__NAMESPACE__' => 'Application\Controller',
        							'controller' => 'Blog',
        							'action'     => 'detalhe',
        					),
        			),
        	),
        	
        	//NEWS
        	'news' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/new[/:slug][/]',
                     'constraints' => array(
                         'slug' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                    'defaults' => array(
                    	'__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'News',
                        'action'     => 'index',
                    ),
                ),
            ),
        	
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
    	'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
        	'Application\Controller\Index' 						=> 'Application\Controller\IndexController',
        	'Application\Controller\Trilha1' 					=> 'Application\Controller\Trilha1Controller',
        	'Application\Controller\Cadastro' 					=> 'Application\Controller\CadastroController',
        	'Application\Controller\Login' 						=> 'Application\Controller\LoginController',
            'Application\Controller\MinhaConta' 				=> 'Application\Controller\MinhaContaController',
        	'Application\Controller\Trilha2' 					=> 'Application\Controller\Trilha2Controller',
        	'Application\Controller\SobreNos' 					=> 'Application\Controller\SobreNosController',
        	'Application\Controller\Contato' 					=> 'Application\Controller\ContatoController',
        	'Application\Controller\Blog' 						=> 'Application\Controller\BlogController',
        	'Application\Controller\Trilha4' 					=> 'Application\Controller\Trilha4Controller',
        	'Application\Controller\Trilha3' 					=> 'Application\Controller\Trilha3Controller',
        	'Application\Controller\Trilha5' 					=> 'Application\Controller\Trilha5Controller',
        	'Application\Controller\Trilha6' 					=> 'Application\Controller\Trilha6Controller',
        	'Application\Controller\Checkout' 					=> 'Application\Controller\CheckoutController',
        	'Application\Controller\ComoFunciona' 				=> 'Application\Controller\ComoFuncionaController',
        	'Application\Controller\Pagamento' 					=> 'Application\Controller\PagamentoController',
        	'Application\Controller\Rotina' 					=> 'Application\Controller\RotinaController',
        	'Application\Controller\PoliticaDePrivacidade' 		=> 'Application\Controller\PoliticaDePrivacidadeController',
        	'Application\Controller\Cartao' 					=> 'Application\Controller\CartaoController',
            'Application\Controller\TermosDeUso' 				=> 'Application\Controller\TermosDeUsoController',
            'Application\Controller\RotinaTestes' 				=> 'Application\Controller\RotinaTestesController',
            'Application\Controller\ParaEmpresas' 				=> 'Application\Controller\ParaEmpresasController',
        ),
    ),
    
		
	'view_manager' => array(
   		'not_found_template'       => '/../view/error/404.phtml',
   		'exception_template'       => '/../view/error/index.phtml',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'error/404/debug'         => __DIR__ . '/../view/error/404_debug.phtml',
            'error/index/debug'       => __DIR__ . '/../view/error/index_debug.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
	
	/*
	* @NAICHE | Deco
	* examples and tests
	* helpers instances
	* plugin instances
	*/
	'controller_plugins' => array(
        'invokables' => array(
            'ExamplePlugin' => 'Application\Controller\Plugin\ExamplePlugin',
        )
    ),
	'view_helpers' => array(
        'invokables'=> array(
        	'example' => 'Application\View\Helper\Example',
        	'pagination' => 'Application\View\Helper\Pagination'
        )
    ),
);
