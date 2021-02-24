<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoVariation extends ModelAbstract
{
	protected $sku;
	protected $ean;
	protected $name;
	protected $external; //Codigo_do_produto_pai_no_outro_sistema
	protected $quantity;
	protected $special_price;
	protected $price;
 
    /**
     * @var ModelPluggtoPhotos
     */
    protected $photos = array();
    
    /**
     * @var ModelPluggtoStockTable
     */
    protected $stock_table = array();
    
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
    
	public function getPhotos()
	{
	    return $this->photos;
	}
	public function addPhotos(ModelPluggtoPhotos $value)
	{
	    $this->photos[] = $value;
	}
	
	public function getStockTable()
	{
	    return $this->stock_table;
	}
	public function addStockTable(ModelPluggtoStockTable $value)
	{
	    $this->stock_table[] = $value;
	}
	

	public function populate($array)
	{
	    $array = (array)$array;
	     
	    //loop no array
	    foreach( $array as $key=>$value )
	    {
	        //add photos
	        if( $key == "photos" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelPluggtoPhotos();
	                $this->addPhotos($model->populate($v));
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
	    }
	     
	    return parent::populate($array);
	}
}