<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoAttribute extends ModelAbstract
{
    protected $code;
    protected $label;
    
    /**
     * @var ModelPluggtoAttributeValue
     */
    protected $value;
    
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
    
    public function getValue()
    {
        return $this->value;
    }
    public function setValue(ModelPluggtoAttributeValue $value)
    {
        $this->value = $value;
    }
}