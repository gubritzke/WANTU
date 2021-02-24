<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelPayment extends ModelAbstract
{
	protected $method;
	protected $description;
	protected $parcels;
	protected $value;
	protected $status;
	
	public function getMethod()
	{
		return $this->method;
	}
	public function setMethod($value)
	{
		$this->method = $value;
	}

	public function getDescription()
	{
	    return $this->description;
	}
	public function setDescription($value)
	{
	    $this->description = $value;
	}

	public function getParcels()
	{
	    return $this->parcels;
	}
	public function setParcels($value)
	{
	    $this->parcels = $value;
	}

	public function getValue()
	{
	    return $this->value;
	}
	public function setValue($value)
	{
	    $this->value = $value;
	}

	public function getStatus()
	{
	    return $this->status;
	}
	public function setStatus($value)
	{
	    $this->status = $value;
	}
}