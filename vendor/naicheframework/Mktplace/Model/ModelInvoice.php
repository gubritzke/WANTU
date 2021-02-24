<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelInvoice extends ModelAbstract
{
    protected $number;
    protected $line;
    protected $key;
    protected $issue_date;
    
    public function getNumber()
	{
		return $this->number;
	}
	public function setNumber($value)
	{
		$this->number = $value;
	}

	public function getLine()
	{
	    return $this->line;
	}
	public function setLine($value)
	{
	    $this->line = $value;
	}

	public function getKey()
	{
	    return $this->key;
	}
	public function setKey($value)
	{
	    $this->key = $value;
	}

	public function getIssueDate()
	{
	    return $this->issue_date;
	}
	public function setIssueDate($value)
	{
	    $this->issue_date = $value;
	}
}