<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelCustomer extends ModelAbstract
{
	protected $name;
	protected $email;
	protected $date_of_birth;
	protected $gender;
	protected $vat_number;
	protected $phones = array();
	
	public function getName()
	{
	    return $this->name;
	}
	public function setName($value)
	{
	    $this->name = $value;
	}

	public function getEmail()
	{
	    return $this->email;
	}
	public function setEmail($value)
	{
	    $this->email = $value;
	}

	public function getDateOfBirth()
	{
	    return $this->date_of_birth;
	}
	public function setDateOfBirth($value)
	{
	    $this->date_of_birth = $value;
	}

	public function getGender()
	{
	    return ($this->gender == "male") ? "Masculino" : "Feminino";
	}
	public function setGender($value)
	{
	    $this->gender = $value;
	}

	public function getVatNumber()
	{
	    return $this->vat_number;
	}
	public function setVatNumber($value)
	{
	    $this->vat_number = $value;
	}

	public function getPhones()
	{
	    return $this->phones;
	}
	public function getPhoneFirst()
	{
	    return current($this->phones);
	}
	public function setPhones($value)
	{
	    $this->phones = $value;
	}
	public function addPhone($value)
	{
	    $this->phones[] = $value;
	}
	
}