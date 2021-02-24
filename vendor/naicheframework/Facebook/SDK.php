<?php
namespace Naicheframework\Facebook;
require_once __DIR__ . '/SDK/autoload.php';

class SDK
{
	private $fb;
	
	public function __construct()
    {
    	$this->fb = new \Facebook\Facebook([
    		'app_id' => '398463630719957',
    		'app_secret' => 'c4d5af532e05a7f8892491a6108485d4',
    		'default_graph_version' => 'v2.10',
    	]);
    }
    
    public function loginRedirect($redirect=null)
    {
    	//definir a url de retorno
    	$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
    	$redirectUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/login/facebook';
    	
    	//solicitar permissões
		$scope = array('email');
    	
    	//link de redirecionamento
    	$helper = $this->fb->getRedirectLoginHelper();
		$loginUrl = $helper->getLoginUrl($redirectUrl, $scope);
		
		//retorna a url de login do facebook
		return $loginUrl;
    }
    
    public function get()
    {
    	try 
    	{
    	    //definir a url de retorno
    	    $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
    	    $redirectUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/login/facebook';
    	    
    		//recuperar token de acesso 
    		$helper = $this->fb->getRedirectLoginHelper();
    		$accessToken = $helper->getAccessToken($redirectUrl);
    		if( empty($accessToken) ) throw new \Exception('Acesso inválido!');
    		
    		//recuperar informações do usuário
    		$response = $this->fb->get('/me?fields=id,name,link,gender,locale,cover,picture.type(large),email', $accessToken);
    		return $response->getGraphUser()->asArray();
    		
    	} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    		
    		// When Graph returns an error
    		throw new \Exception('Graph returned an error: ' . $e->getMessage());
    		
    	} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    		
    		// When validation fails or other local issues
    		throw new \Exception('Facebook SDK returned an error: ' . $e->getMessage());
    		
    	}
    }
}