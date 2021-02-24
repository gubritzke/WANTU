<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelItem extends ModelAbstract
{
	protected $id;
	protected $product_id;
	protected $sku;
	protected $name;
	protected $qty;
	protected $original_price;
	protected $special_price;
	
	/**
	 * informações extras do item
	 */
	protected $extra;
	
	public function getId()
	{
		return $this->id;
	}
	public function setId($value)
	{
		$this->id = $value;
	}

	public function getProductId()
	{
	    return $this->product_id;
	}
	public function setProductId($value)
	{
	    $this->product_id = $value;
	}
	
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
	
	public function getQty()
	{
	    return $this->qty;
	}
	public function setQty($value)
	{
	    $this->qty = $value;
	}
    
	public function getOriginalPrice()
	{
	    return $this->original_price;
	}
	public function setOriginalPrice($value)
	{
	    $this->original_price = $value;
	}
    
	public function getSpecialPrice()
	{
	    return $this->special_price;
	}
	public function setSpecialPrice($value)
	{
	    $this->special_price = $value;
	}
	
	public function getExtra()
	{
	    return $this->extra;
	}
	public function setExtra($value)
	{
	    $this->extra = $value;
	}
	
	/**
	 * retorna o valor da chave pesquisada, se existir
	 */
	public function getExtraByKey($value)
	{
	    return !empty($this->extra->$value) ? $this->extra->$value : null;
	}
	
	/**
	 * retorna o preço final
	 */
	public function getPrice()
	{
	    return !empty($this->special_price) ? $this->special_price : $this->original_price;
	}
}