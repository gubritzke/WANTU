<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoStockTable extends ModelAbstract
{
    protected $code;
    protected $type;
    protected $priority;
    protected $quantity;
    protected $price;
    protected $special_price;
    protected $handling_time;
    
    public function getCode()
    {
        return $this->code;
    }
    public function setCode($value)
    {
        $this->code = $value;
    }
    
    public function getType()
    {
        return $this->type;
    }
    public function setType($value)
    {
        $this->type = $value;
    }
    
    public function getPriority()
    {
        return $this->priority;
    }
    public function setPriority($value)
    {
        $this->priority = $value;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
    public function setQuantity($value)
    {
        $this->quantity = $value;
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
    
    public function getHandlingTime()
    {
        return $this->handling_time;
    }
    public function setHandlingTime($value)
    {
        $this->handling_time = $value;
    }
}