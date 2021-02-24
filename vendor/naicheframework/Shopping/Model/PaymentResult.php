<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class PaymentResult extends ModelAbstract
{
	protected $id;
	protected $status;
	protected $detail;
    
	public function getId()
	{
	    return $this->id;
	}
	
	public function setId($value)
	{
	    $this->id = $value;
	}
    
	public function getStatus()
	{
	    return $this->status;
	}
	
	public function setStatus($value)
	{
	    $this->status = $value;
	}
    
	public function getDetail()
	{
	    return $this->detail;
	}
	
	public function setDetail($value)
	{
	    $this->detail = $value;
	}
}