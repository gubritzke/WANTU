<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelStatus extends ModelAbstract
{
    protected $type;
    protected $label;
    protected $code;

    public function getType()
    {
        return $this->type;
    }
    public function setType($value)
    {
        $this->type = $value;
    }

    public function geLabel()
    {
        return $this->label;
    }
    public function setLabel($value)
    {
        $this->label = $value;
    }

    public function getCode()
    {
        return $this->code;
    }
    public function setCode($value)
    {
        $this->code = $value;
    }
	
}