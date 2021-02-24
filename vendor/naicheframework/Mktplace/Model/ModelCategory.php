<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelCategory extends ModelAbstract
{
	protected $code;
	protected $name;
	
	public function getCode()
	{
		return $this->code;
	}
	public function setCode($value)
	{
		$this->code = $value;
	}

	public function getName()
	{
	    return $this->name;
	}
	public function setName($value)
	{
	    $this->name = $value;
	}
	
}