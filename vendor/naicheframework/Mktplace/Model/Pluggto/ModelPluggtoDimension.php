<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoDimension extends ModelAbstract
{
    protected $length;
    protected $width;
    protected $height;
    protected $weight;

    public function getLength()
    {
        return $this->length;
    }
    public function setLength($value)
    {
        $this->length = (int)$value;
    }

    public function getWidth()
    {
        return $this->width;
    }
    public function setWidth($value)
    {
        $this->width = (int)$value;
    }
    
    public function getHeight()
    {
        return $this->height;
    }
    public function setHeight($value)
    {
        $this->height = (int)$value;
    }
    
	public function getWeight()
	{
	    return $this->weight;
	}
	public function setWeight($value)
	{
	    //converter em inteiro
	    $value = (int)$value;
	    
	    //converter gramas em kg
	    $this->weight = $value / 1000;
	}
}