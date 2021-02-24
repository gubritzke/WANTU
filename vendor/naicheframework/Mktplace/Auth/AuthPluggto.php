<?php
namespace Naicheframework\Mktplace\Auth;

/**
 * @NAICHE | Vitor Deco
 */
class AuthPluggto extends AuthAbstract
{
    //user production
    protected $client_id                = '8613263ccc418f1808c5f455daeb1435'; //Plugin API id
    protected $client_secret            = '85829fb695d8d573b272b2319fc46ce9'; //Plugin API password
    protected $app_user                 = '1527536830'; //User API key
    protected $app_password             = 'dml0b3JAbmFpY2hlLmNvbS5icjViMGM1YmRhOTFiN2Q0OA=='; //User API password
    
    //user sandbox
    protected $sandbox_client_id        = '8613263ccc418f1808c5f455daeb1435';
    protected $sandbox_client_secret    = '85829fb695d8d573b272b2319fc46ce9';
    protected $sandbox_app_user         = '1527536830';
    protected $sandbox_app_password     = 'dml0b3JAbmFpY2hlLmNvbS5icjViMGM1YmRhOTFiN2Q0OA==';
    
    //endpoint
	protected $url = "https://api.plugg.to";
	
	//api
	protected $api;
	
	public function __construct()
	{
	    //definir tipo de integração
	    parent::__construct("PLUGGTO");
	    
	    //definir api
	    $this->api = new \Naicheframework\Api\Request($this->url);
	    $this->api->setJsonData(true);
	}
	
	public function setSandbox($bool=true)
	{
	    if( $bool === true )
	    {
	        $this->client_id = $this->sandbox_client_id;
	        $this->client_secret = $this->sandbox_client_secret;
	        $this->app_user = $this->sandbox_app_user;
	        $this->app_password = $this->sandbox_app_password;
	    }
	    
	    parent::setSandbox($bool);
	    return $this;
	}
	
	public function getAccessToken()
	{
	    //To get a access_token from plugin authetication method you need 4 credencials.
	    $data = [
	        "client_id" => $this->client_id,
	        "client_secret" => $this->client_secret,
	        "username" => $this->app_user,
	        "password" => $this->app_password,
	        "grant_type" => "password",
	    ];
	    
	    $request = new \Naicheframework\Api\Request($this->url);
        $response = $request->call("oauth/token", $data)->result();
        
        return $response->access_token;
	}
	
	public function getApi()
	{
	    return $this->api;
	}
}