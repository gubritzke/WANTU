<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelVariation extends ModelAbstract
{
	protected $sku;
	protected $qty;
	protected $ean;
	
	/**
	 * @var array
	 */
	protected $images = array();
	
	/**
	 * @var ModelSpecification
	 */
	protected $specifications = array();
	
	public function getSku()
	{
		return $this->sku;
	}
	public function setSku($value)
	{
		$this->sku = $value;
	}
    
	public function getQty()
	{
	    return $this->qty;
	}
	public function setQty($value)
	{
	    $this->qty = $value;
	}
    
	public function getEan()
	{
	    return $this->ean;
	}
	public function setEan($value)
	{
	    $this->ean = $value;
	}

	public function getImages()
	{
	    return $this->images;
	}
	public function setImages($value)
	{
	    $this->images = $value;
	}
	public function addImage($value)
	{
        if( !empty($value) ) $this->images[] = $value;
	}
	
	public function getSpecifications()
	{
	    return $this->specifications;
	}
	public function addSpecification(ModelSpecification $value)
	{
	    $this->specifications[] = $value;
	}
	
	public function populate($array)
	{
	    $array = (array)$array;
	     
	    //loop no array
	    foreach( $array as $key=>$value )
	    {
	         
	        //add specifications
	        if( $key == "specifications" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelSpecification();
	                $this->addSpecification($model->populate($v));
	            }
	        }
	        
	    }
	     
	    return parent::populate($array);
	}
}