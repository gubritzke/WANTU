<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoProduct extends ModelAbstract
{
	protected $sku;
	protected $ean;
	protected $name;
	protected $external; //codigo do produto pai de referencia no sistema
	protected $quantity;
	protected $special_price;
	protected $price;
	protected $short_description;
	protected $description;
	protected $brand;
	protected $cost;
    protected $warranty_time;
    protected $warranty_message;
    protected $link;
    protected $available;
    protected $status;
	
    /**
     * @var ModelPluggtoCategory
     */
    protected $categories = array();
    
    protected $handling_time;
    protected $manufacture_time;
 
    /**
     * @var ModelPluggtoDimension
     */
    protected $dimension;
    
    /**
     * @var ModelPluggtoAttribute
     */
    protected $attributes = array();
    
    /**
     * @var ModelPluggtoPhoto
     */
    protected $photos = array();
    
    /**
     * @var ModelPluggtoPriceTable
     */
    protected $price_table = array();
    
    /**
     * @var ModelPluggtoStockTable
     */
    protected $stock_table = array();
    
    /**
     * @var ModelPluggtoVariation
     */
    protected $variations = array();
	
	public function getSku()
	{
		return $this->sku;
	}
	public function setSku($value)
	{
		$this->sku = $value;
	}

	public function getEan()
	{
	    return $this->ean;
	}
	public function setEan($value)
	{
	    $this->ean = $value;
	}
	
	public function getName()
	{
	    return $this->name;
	}
	public function setName($value)
	{
	    $this->name = $value;
	}

	public function getExternal()
	{
	    return $this->external;
	}
	public function setExternal($value)
	{
	    $this->external = $value;
	}

	public function getQuantity()
	{
	    return $this->quantity;
	}
	public function setQuantity($value)
	{
	    $this->quantity = $value;
	}

	public function getSpecialPrice()
	{
	    return $this->special_price;
	}
	public function setSpecialPrice($value)
	{
	    $this->special_price = $value;
	}

	public function getPrice()
	{
	    return $this->price;
	}
	public function setPrice($value)
	{
	    $this->price = $value;
	}

	public function getShortDescription()
	{
	    return $this->short_description;
	}
	public function setShortDescription($value)
	{
	    $this->short_description = $value;
	}
	
	public function getDescription()
	{
	    return $this->description;
	}
	public function setDescription($value)
	{
	    $this->description = $value;
	}

	public function getBrand()
	{
	    return $this->brand;
	}
	public function setBrand($value)
	{
	    $this->brand = $value;
	}

	public function getCost()
	{
	    return $this->cost;
	}
	public function setCost($value)
	{
	    $this->cost = $value;
	}

	public function getWarrantyTime()
	{
	    return $this->warranty_time;
	}
	public function setWarrantyTime($value)
	{
	    $this->warranty_time = $value;
	}

	public function getWarrantyMessage()
	{
	    return $this->warranty_message;
	}
	public function setWarrantyMessage($value)
	{
	    $this->warranty_message = $value;
	}

	public function getLink()
	{
	    return $this->link;
	}
	public function setLink($value)
	{
	    $this->link = $value;
	}

	public function getAvailable()
	{
	    return $this->available;
	}
	public function setAvailable($value)
	{
	    $this->available = $value;
	}

	public function getStatus()
	{
	    return $this->status;
	}
	public function setStatus($value)
	{
	    $this->status = $value;
	}
	
	public function getCategories()
	{
	    return $this->categories;
	}
	public function addCategory(ModelPluggtoCategory $value)
	{
	    $this->categories[] = $value;
	}
	
	public function getHandlingTime()
	{
	    return $this->handling_time;
	}
	public function setHandlingTime($value)
	{
	    $this->handling_time = $value;
	}

	public function getManufactureTime()
	{
	    return $this->manufacture_time;
	}
	public function setManufactureTime($value)
	{
	    $this->manufacture_time = $value;
	}

	public function getDimension()
	{
	    return $this->dimension;
	}
	public function setDimension(ModelPluggtoDimension $value)
	{
	    $this->dimension = $value;
	}
	
	public function getAttributes()
	{
	    return $this->attributes;
	}
	public function addAttribute(ModelPluggtoAttribute $value)
	{
	    $this->attributes[] = $value;
	}

	public function getPhotos()
	{
	    return $this->photos;
	}
	public function addPhoto(ModelPluggtoPhoto $value)
	{
	    if( !empty($value->getUrl()) )
	    {
	       $this->photos[] = $value;
	    }
	}
	
	public function getPriceTable()
	{
	    return $this->price_table;
	}
	public function addPriceTable(ModelPluggtoPriceTable $value)
	{
	    $this->price_table[] = $value;
	}
	
	public function getStockTable()
	{
	    return $this->stock_table;
	}
	public function addStockTable(ModelPluggtoStockTable $value)
	{
	    $this->stock_table[] = $value;
	}
	
	public function getVariations()
	{
	    return $this->variations;
	}
	public function addVariation(ModelPluggtoVariation $value)
	{
	    $this->variations[] = $value;
	}
	
	public function populate($array)
	{
	    $array = (array)$array;
	    
	    //loop no array
	    foreach( $array as $key=>$value )
	    {
	        //add categories
	        if( $key == "categories" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelPluggtoCategory();
	                $this->addCategory($model->populate($v));
	            }
	        }
	        
	        //add attributes
	        if( $key == "attributes" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelPluggtoAttribute();
	                $this->addAttribute($model->populate($v));
	            }
	        }
	        
	        //add photos
	        if( $key == "photos" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelPluggtoPhoto();
	                $this->addPhoto($model->populate($v));
	            }
	        }
	        
	        //add price_table
	        if( $key == "price_table" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelPluggtoPriceTable();
	                $this->addPriceTable($model->populate($v));
	            }
	        }
	        
	        //add stock_table
	        if( $key == "stock_table" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelPluggtoStockTable();
	                $this->addStockTable($model->populate($v));
	            }
	        }
	        
	        //add variations
	        if( $key == "variations" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelPluggtoVariation();
	                $this->addVariation($model->populate($v));
	            }
	        }
	    }
	    
	    return parent::populate($array);
	}
}