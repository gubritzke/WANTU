<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoPriceTable extends ModelAbstract
{
    protected $code;
    protected $label;
    protected $price;
    protected $special_price;
    protected $action;
    
    public function getCode()
    {
        return $this->code;
    }
    public function setCode($value)
    {
        $this->code = $value;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    public function setLabel($value)
    {
        $this->label = $value;
    }
    
    public function getPrice()
    {
        return $this->price;
    }
    public function setPrice($value)
    {
        $this->price = $value;
    }
    
    public function getSpecialPrice()
    {
        return $this->special_price;
    }
    public function setSpecialPrice($value)
    {
        $this->special_price = $value;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    public function setAction($value)
    {
        $this->action = $value;
    }
}