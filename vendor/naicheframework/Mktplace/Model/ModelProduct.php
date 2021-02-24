<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelProduct extends ModelAbstract
{
	protected $sku;
	protected $name;
	protected $description;
	protected $status;
	protected $removed;
	protected $qty;
	protected $price;
	protected $promotional_price;
	protected $cost;
	protected $weight;
	protected $height;
	protected $width;
	protected $length;
	protected $brand;
	protected $ean;
	protected $nbm;
	
	/**
	 * @var ModelCategory
	 */
	protected $categories = array();
	
	/**
	 * @var array
	 */
	protected $images = array();
	
	/**
	 * @var ModelSpecification
	 */
	protected $specifications = array();
	
	/**
	 * @var ModelVariation
	 */
	protected $variations = array();
	
	/**
	 * @var array
	 */
	protected $variation_attributes = array();
	
	public function getSku()
	{
		return $this->sku;
	}
	public function setSku($value)
	{
		$this->sku = $value;
	}

	public function getName()
	{
	    return $this->name;
	}
	public function setName($value)
	{
	    $this->name = $value;
	}

	public function getDescription()
	{
	    return $this->description;
	}
	public function setDescription($value)
	{
	    $this->description = $value;
	}

	public function getStatus()
	{
	    return $this->status;
	}
	public function setStatus($value)
	{
	    $this->status = $value;
	}

	public function getRemoved()
	{
	    return $this->removed;
	}
	public function setRemoved($value)
	{
	    $this->removed = $value;
	}
	
	public function getQty()
	{
	    return $this->qty;
	}
	public function setQty($value)
	{
	    $this->qty = $value;
	}

	public function getPrice()
	{
	    return $this->price;
	}
	public function setPrice($value)
	{
	    $this->price = $value;
	}

	public function getPromotionalPrice()
	{
	    return $this->promotional_price;
	}
	public function setPromotionalPrice($value)
	{
	    $this->promotional_price = $value;
	}

	public function getCost()
	{
	    return $this->cost;
	}
	public function setCost($value)
	{
	    $this->cost = $value;
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

	public function getHeight()
	{
	    return $this->height;
	}
	public function setHeight($value)
	{
	    $this->height = (int)$value;
	}

	public function getWidth()
	{
	    return $this->width;
	}
	public function setWidth($value)
	{
	    $this->width = (int)$value;
	}

	public function getLength()
	{
	    return $this->length;
	}
	public function setLength($value)
	{
	    $this->length = (int)$value;
	}

	public function getBrand()
	{
	    return $this->brand;
	}
	public function setBrand($value)
	{
	    $this->brand = $value;
	}

	public function getEan()
	{
	    return $this->ean;
	}
	public function setEan($value)
	{
	    $this->ean = $value;
	}

	public function getNbm()
	{
	    return $this->nbm;
	}
	public function setNbm($value)
	{
	    $this->nbm = $value;
	}

	public function getCategories()
	{
	    return $this->categories;
	}
	public function addCategory(ModelCategory $value)
	{
	    $this->categories[] = $value;
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
	public function clearSpecifications()
	{
	    $this->specifications = array();
	}

	public function getVariations()
	{
	    return $this->variations;
	}
	public function addVariation(ModelVariation $value)
	{
	    $this->variations[] = $value;
	}

	public function getVariationAttributes()
	{
	    return $this->variation_attributes;
	}
	public function setVariationAttributes($value)
	{
	    $this->variation_attributes = $value;
	}
	public function addVariationAttribute($value)
	{
	    $this->variation_attributes[] = $value;
	}
	
	public function populate($array)
	{
	    $array = (array)$array;
	    
	    //loop no array
	    foreach( $array as $key=>$value )
	    {
	        //add status
	        if( $key == "status" )
	        {
	            $this->setStatus($value);
	            unset($array[$key]);
	        }
	        
	        //add categories
	        if( $key == "categories" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelCategory();
	                $this->addCategory($model->populate($v));
	            }
	        }

	        //add specifications
	        if( $key == "specifications" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelSpecification();
	                $this->addSpecification($model->populate($v));
	            }
	        }
	        
	        //add variatons
	        if( $key == "variations" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelVariation();
	                $this->addVariation($model->populate($v));
	            }
	        }
	    }
	    
	    return parent::populate($array);
	}
}