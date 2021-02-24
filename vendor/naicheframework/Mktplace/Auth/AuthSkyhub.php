<?php
namespace Naicheframework\Mktplace\Auth;

/**
 * @NAICHE | Vitor Deco
 */
class AuthSkyhub extends AuthAbstract
{
    //user production
    protected $accountmanager   = 'R3dMYZzDSZ';
    protected $apikey           = 'nZ-asUu7gkf4qEFLxAjJ';
    protected $useremail        = 'leandro@naiche.com.br';
    
    //user sandbox
    protected $sandbox_accountmanager   = 'R3dMYZzDSZ';
    protected $sandbox_apikey           = 'qDrA-7xP9xHXAdcKjd-5';
    protected $sandbox_useremail        = 'vitor@naiche.com.br';
    
    //endpoint
	protected $url              = "https://api.skyhub.com.br";
	
	//api
	protected $api;
	
	public function __construct()
	{
	    //definir api
	    $this->api = new \Naicheframework\Api\Request($this->url);
	    $this->api->setJsonData(true);
	    $this->api->addHeader("x-accountmanager-key: " . $this->accountmanager);
	    $this->api->addHeader("x-api-key: " . $this->apikey);
	    $this->api->addHeader("x-user-email: " . $this->useremail);
	    
	    //definir tipo de integração
	    parent::__construct("SKYHUB");
	}
	
	public function setSandbox($bool=true)
	{
	    if( $bool === true )
	    {
	        //definir api
	        $this->api = new \Naicheframework\Api\Request($this->url);
	        $this->api->setJsonData(true);
	        $this->api->addHeader("x-accountmanager-key: " . $this->sandbox_accountmanager);
	        $this->api->addHeader("x-api-key: " . $this->sandbox_apikey);
	        $this->api->addHeader("x-user-email: " . $this->sandbox_useremail);
	    }
	     
	    parent::setSandbox($bool);
	    return $this;
	}
	
	public function getApi()
	{
	    return $this->api;
	}
}