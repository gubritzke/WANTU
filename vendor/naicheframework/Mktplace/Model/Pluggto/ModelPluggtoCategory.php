<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoCategory extends ModelAbstract
{
    protected $name;
    
    public function getName()
    {
        return $this->name;
    }
    public function setName($value)
    {
        $this->name = $value;
    }
}