<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class PromotionMultiple extends ModelAbstract
{
    /**
     * @var Promotion
     */	
	protected $promotion = array();
	
	/**
	 * adicionar uma nova promoção
	 * @param Promotion $promotion
	 */
	public function addPromotion(Promotion $promotion)
	{
		$this->promotion[] = $promotion;
	}
	
    /**
	 * @return Promotion
	 */
	public function getPromotion()
	{
		return $this->promotion;
	}
	
	/**
	 * @param string $value
	 * @return Order
	 */
	public function getPromotionById($value)
	{
	    foreach( $this->promotion as $promotion )
	    {
	        if( $promotion->getId() == $value )
	        {
	            return $promotion;
	        }
	    }
	
	    $promotion = new Promotion();
	    return $promotion;
	}
}