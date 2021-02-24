<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoAttributeValue extends ModelAbstract
{
    protected $code;
    protected $label;
    
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
}