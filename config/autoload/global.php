<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
		//config do gateway de pagamento
// 		'config_payment'  => array(
// 				'moip' => array(
// 						'public_key' => '-----BEGIN PUBLIC KEY-----
// MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi5RpE8Wfd637c2Ee4nq3
// Ht1qX1k2Q1ATMA16BJcmBHPzl0vVrMuh57vxsTf0gvjV96LMcTlkXzN46nYCPxcP
// B5MFglJZZlZboKBkssVNrzuzRZ2s0Bfm+H1GhGpx0MgwhhgWg1brdfkrkcS4hbH1
// 55BYbC2C5941fhoU9QLnd6PIRpoOjkWJdf5pPV0kMX1tNO/ZzU2My46X4lhc6XeZ
// y33Vu3OgsViAuTLdbv77QHjR4HTpDFDI0ky5l/xB+ZSCYJUR5Doqq44Jdt1EF+tn
// TvukfMRUobi3M9xBl5wFJ93KxbOqfo6mxN6BhpVtTKrj5IxWsi7P+6fpgcmmEB/i
// eQIDAQAB
// -----END PUBLIC KEY-----',
// 						'key_js' => 'YASL8YPH2CUXXFJ8OPDD3N3I3ATBGUEX',
// 						'token' => 'YJYRUSYBOIDYWOHAMD5IGYJWPKRJGT0O',
// 						'key' => 'O7RK8CYUL5L6DHA3MHOOMHURBSSIKKZ0TIZGIDTX',
// 						'moip_id' => 'MPA-629DFF9345D1',
// 				),
// 		),

		'config_payment'  => array(
				'moip' => array(
						'public_key' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2u5PxvSa5kmjAoqxPswc
kQl0nkM6Vhkwp9LGNpMIoc6Qpd38nxH/6qzeuT5ipf6uFXOItduyjs5y6woD1e0w
x5/jzTB1qxjIx9SkQxPwUx6yz1LmdR7N3EVxjJqKq5VPcdxds1hcP7uqBojVviEg
bzi54nCGd3XE7DhdM6y+G9IoCv3jhv+GyVETtPnS1f8Rz5qX+xKgKIJAoChFlS47
/ce0q4B4gfaMhwQOy5Yj6zB/KrCGXJMaV2NGzoylX3LcxSAIOE4DuiPUAAOzD7be
iRJFjvODpdnXvQWbm2AWKmH9lABC1/j94cRJGr0frXQSIU3HqSUVT0Z0I8L6AiAV
uwIDAQAB
-----END PUBLIC KEY-----',
						'key_js' => 'RVJLN3LGWZTQJT5FEBEFS4DBUQEHKBC3',
						'token' => 'T2CACDYCWTIP3FNJ8N1BB9IWFKAS5YMT',
						'key' => 'KUDLNR6BDLTBK2KTLXOQBZZZQTL7USVMAAYRG6FN',
						'moip_id' => 'MPA-93ECD4850A84',
				),
		),
		
		
	'db' => array(
		'driver'	=> 'Pdo',
		'dsn'		=> 'mysql:dbname=adwsd_wantu;host=wantu.com.br',
		'username'	=> 'adwsd_naiche',
		'password'	=> '86bN]a{S1}Vc',
		'driver_options' => array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
		),
	),
	
	'tb' => array(
		'analise_de_curriculo'						=> 'wt_analise_de_curriculo',
		'analise_cursos'							=> 'wt_analise_cursos',
		'analise_experiencia_profissional'			=> 'wt_analise_experiencia_profissional',
		'analise_experiencia_internacional'			=> 'wt_analise_experiencia_internacional',
		'analise_entrevista'						=> 'wt_analise_entrevista',
		'login'										=> 'wt_login',
		'perfil_comportamental'						=> 'wt_perfil_comportamental',
		'contato'									=> 'wt_contato',
		'blog'										=> 'wt_blog',
		'aptidoes_profissionais'					=> 'wt_aptidoes_profissionais',
		'inteligencias_multiplas'					=> 'wt_inteligencias_multiplas',
		'pontos_fortes'								=> 'wt_pontos_fortes',
		'competencias'								=> 'wt_competencias',
		'planos_usuario'							=> 'wt_planos_usuario',
		'planos'									=> 'wt_planos',
		'dados_escolares'							=> 'wt_dados_escolares',
		'pos_graduacao'								=> 'wt_pos_graduacao',
		'idiomas'									=> 'wt_idiomas',
		'cupom'										=> 'wt_cupom',
	    'resultados_finais'							=> 'wt_resultados_finais',
	    'resultados_finais_completo'				=> 'wt_resultados_finais_completo',
	    'para_empresas'				                => 'wt_para_empresas',
),
	
	'service_manager' => array(
		'factories' => array(
			'db' => 'Zend\Db\Adapter\AdapterServiceFactory',
		),
	),
	
	'view_manager' => array(
	    'base_path' 				=> '/',
	    'display_not_found_reason'	=> false,
		'display_exceptions'		=> false,
	    'doctype'                  	=> 'HTML5',
	    'not_found_template'       	=> 'error/404',
	    'exception_template'       	=> 'error/index',
    ),
    
    'phpSettings'   => array(
    	'display_startup_errors'        => false,
    	'display_errors'                => false,
	    'error_reporting'               => 0,
	    'max_execution_time'            => 60,
	    'date.timezone'                 => 'America/Sao_Paulo',
	    'default_charset'               => 'UTF-8',
        
        'session.cookie_httponly'       => true,
        'session.cookie_secure'         => true,
        'expose_php'                    => false,
    ),
    
 	//Config Host's Api
 	'config_host' => array(
 		'env' => 'production',
 	),
 	
 	//Config send smtp
	'config_smtp'   => array(
		'name' 			=> 'Wantu',
		'host' 			=> 'mail.wantu.com.br',
		'port' 			=> 465,
		'connClass'		=> 'login',
		'username' 		=> 'no-reply@wantu.com.br',
		'password' 		=> 'F4g!Xp2N(11e',
		'ssl'			=> 'ssl',
		'addFrom' 		=> 'no-reply@wantu.com.br',
		'setSubject'	=> 'Wantu',
	),
);