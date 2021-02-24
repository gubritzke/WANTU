<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelTrack extends ModelAbstract
{
    protected $code;
    protected $carrier;
    protected $method;
    protected $url;    
    
    public function getCode()
    {
        return $this->code;
    }
    public function setCode($value)
    {
        $this->code = $value;
    }
    
    public function getCarrier()
    {
        return $this->carrier;
    }
    public function setCarrier($value)
    {
        $this->carrier = $value;
    }

    public function getMethod()
    {
        return $this->method;
    }
    public function setMethod($value)
    {
        $this->method = $value;
    }

    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($value)
    {
        $this->url = $value;
    }
}