<?php
namespace Naicheframework\Api;

class Request
{
	protected $url = null;
	
	protected $urlExtra = null;
	
	protected $authorization = null;
	
	protected $jsonData = false;
	
	protected $header = array();
	
	protected $debug = array();
	
	protected $result = array();
	
	private $model = null;
	
	public function __construct($api, $authorization=null)
	{
		$this->setUrl($api);
		$this->setAuthorization($authorization);
	}
	
	public function setUrl($url)
	{
		$this->url = $url;
	}
	public function getUrl()
	{
		return $this->url;
	}
	
	public function setUrlExtra($str)
	{
		$this->urlExtra = $str;
	}
	public function getUrlExtra()
	{
		return $this->urlExtra;
	}
	
	public function setAuthorization($auth)
	{
		$this->authorization = $auth;
	}
	public function getAuthorization()
	{
		return $this->authorization;
	}
	
	public function setJsonData($bool)
	{
	    $this->jsonData = (bool)$bool;
	    return $this;
	}
	public function getJsonData()
	{
	    return $this->jsonData;
	}
	
	public function addHeader($header)
	{
	    $this->header[] = $header;
	}
	public function getHeader()
	{
	    return $this->header;
	}
	
	public function model($model)
	{
	    //verificar se existe o método populate
	    if( method_exists($model, "populate") )
	    {
	        $this->model = $model;
	    }
	     
	    return $this;
	}
	
	public function call($url, $params=[], $type='POST')
	{
		//definir url
		if( !empty($this->urlExtra) )
		{
			$url = $this->url . '/' . $this->urlExtra . '/' . $url;
		} else {
			$url = $this->url . '/' . $url;
		}
		
		//definir headers
		$header = array();
		$header[] = "Accept: application/json";
		
		if( !empty($this->authorization) )
		{
		    $header[] = $this->authorization;
		}
		
		if( !empty($this->header) )
		{
		    $header = array_merge($header, $this->header);
		}
		
		//definir tipo de envio dos dados
		if( $this->jsonData === true )
		{
		    $header[] = "Content-type: application/json";
		    $data = json_encode($params);
		    
		} else {
		    $data = http_build_query($params);
		}
		
		//iniciar função curl
		$ch = curl_init();
		
		//verificar se o tipo é GET
		if( $type == 'GET' && count($params) )
		{
            $url .= '?' . http_build_query($params);
		}
		
		//verificar se o tipo é POST
		if( $type == 'POST' ){
		    	
		    curl_setopt($ch, CURLOPT_POST, 1);
		}
		
		//definir as opções do curl
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
		curl_setopt($ch, CURLOPT_URL, $url);
		
		//executar curl
		$response = curl_exec($ch);
		$response = json_decode($response);
		
		//salvar informações da requisição
		$this->debug = curl_getinfo($ch);
		
		//salvar resultado
		$this->result = $response;
		
		//retorna o this
		return $this;
	}
	
	/**
	 * exibe o debug
	 */
	public function debug()
	{
		echo'<pre>';
		print_r($this->debug);
		print_r($this->result);
		exit;
	}
	
	/**
	 * retorna o resultado
	 */
	public function result()
	{
	    //verificar se existe um model ativo
	    if( !empty($this->model) )
	    {
	        //loop nos resultados
	        foreach( $this->result as &$value )
	        {
	            //converter resultado para model ativo
	            $value = $this->model->populate($value);
	        }
	    }
	    
	    return $this->result;
	}
	
	/**
	 * retorna o primeiro elemento do resultado
	 */
	public function current()
	{
	    //recuperar o primeiro registro do array
		$this->result = current($this->result);
		
		//verificar se existe um model ativo
		if( !empty($this->model) )
		{
	        //converter resultado para model ativo
	        $this->result = $this->model->populate($this->result);
		}
		
		return $this->result;
	}
	
	/**
	 * retorna a quantidade de registros
	 */
	public function count()
	{
		return count($this->result);
	}
}