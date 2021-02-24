<?php
namespace Naicheframework\Mktplace\Auth;

/**
 * @NAICHE | Vitor Deco
 */
abstract class AuthAbstract
{
    /**
     * identifica qual é a integração
     * @var string
     */
    private $type = null;
    
	/**
	 * identifica em qual ambiente está
	 * @var boolean
	 */
	protected $is_sandbox = false;
	
	/**
	 * construtor definindo qual integração será usada
	 * @param string $integration_type
	 */
	public function __construct($type)
	{
		$this->type = $type;
	}
	
	/**
	 * define o ambiente
	 * @param boolean $bool
	 * @return IntegrationAbstract
	 */
	public function setSandbox($bool=true)
	{
		$this->is_sandbox = (bool)$bool;
		return $this;
	}
	
	/**
	 * retorna se o ambiente é de teste
	 * @return boolean
	 */
	public function isSandbox()
	{
	    return $this->is_sandbox;
	}
	
}