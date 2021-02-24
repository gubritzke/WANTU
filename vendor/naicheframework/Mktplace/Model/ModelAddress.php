<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelAddress extends ModelAbstract
{
    protected $street;
    protected $number;
    protected $detail;
    protected $neighborhood;
    protected $reference;
    protected $city;
    protected $region;
    protected $country;
    protected $postcode;

    public function getStreet()
    {
        return $this->street;
    }
    public function setStreet($value)
    {
        $this->street = $value;
    }

    public function getNumber()
    {
        return $this->number;
    }
    public function setNumber($value)
    {
        $this->number = $value;
    }

    public function getDetail()
    {
        return $this->detail;
    }
    public function setDetail($value)
    {
        $this->detail = $value;
    }

    public function getNeighborhood()
    {
        return $this->neighborhood;
    }
    public function setNeighborhood($value)
    {
        $this->neighborhood = $value;
    }

    public function getReference()
    {
        return $this->reference;
    }
    public function setReference($value)
    {
        $this->reference = $value;
    }

    public function getCity()
    {
        return $this->city;
    }
    public function setCity($value)
    {
        $this->city = $value;
    }

    public function getRegion()
    {
        return $this->region;
    }
    public function setRegion($value)
    {
        $this->region = $value;
    }

    public function getCountry()
    {
        return $this->country;
    }
    public function setCountry($value)
    {
        $this->country = $value;
    }
    
    public function getPostcode()
    {
    	return $this->postcode;
    }
    public function setPostcode($value)
    {
    	$value = str_replace('-', '', $value);
    	$this->postcode = $value;
    }
    
}