<?php
return array(
		'router' => array(
				'routes' => array(
						// The following is a route to simplify getting started creating
						// new controllers and actions without needing to create a new
						// module. Simply drop new controllers in, and you can access them
						// using the path /painel/:controller/:action
						'painel' => array(
								'type'    => 'Literal',
								'options' => array(
										'route'    => '/painel',
										'defaults' => array(
												'__NAMESPACE__' => 'Painel\Controller',
												'controller'    => 'Index',
												'action'        => 'index',
										),
								),
								'may_terminate' => true,
								'child_routes' => array(
										'default' => array(
												'type'    => 'Segment',
												'options' => array(
														'route'    => '/[:controller[/:action][/:id][/:id2]][/]',
														'constraints' => array(
																'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
																'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
														),
														'defaults' => array(
														),
												),
										),
								),
						),
				),
		),
		'controllers' => array(
				'invokables' => array(
						'Painel\Controller\Index' 				=> 'Painel\Controller\IndexController',
						'Painel\Controller\Login'				=> 'Painel\Controller\LoginController',
						'Painel\Controller\RelatoriosGerais' 	=> 'Painel\Controller\RelatoriosGeraisController',
						'Painel\Controller\Contato' 			=> 'Painel\Controller\ContatoController',
						'Painel\Controller\Blog' 				=> 'Painel\Controller\BlogController',
						'Painel\Controller\RelatorioFinal' 		=> 'Painel\Controller\RelatorioFinalController',
						'Painel\Controller\Usuarios' 		    => 'Painel\Controller\UsuariosController',
				        'Painel\Controller\ParaEmpresas' 		=> 'Painel\Controller\ParaEmpresaController',
				),
		),
		'view_manager' => array(
				'template_map' => array(
						'painel/layout'           => __DIR__ . '/../view/layout/layout.phtml',
						'painel/login'            => __DIR__ . '/../view/layout/login.phtml',
				),
				'template_path_stack' => array(
						'Painel' => __DIR__ . '/../view',
				),
		),
);
