<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelSpecification extends ModelAbstract
{
	protected $key;
	protected $value;
	
	public function getKey()
	{
		return $this->key;
	}
	public function setKey($value)
	{
		$this->key = $value;
	}

	public function getValue()
	{
	    return $this->value;
	}
	public function setValue($value)
	{
	    $this->value = $value;
	}
	
}