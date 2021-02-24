<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class ShipmentMultiple extends ModelAbstract
{
	/**
	 * @var Shipment
	 */
	protected $itens = array();
	
	public function toArray()
	{
		$result = array();
		foreach( $this->itens as $item ) $result[$item->getServico()] = $item->toArray();
		return $result;
	}
	
	public function toObject()
	{
	    $result = array();
	    foreach( $this->itens as $item ) $result[$item->getServico()] = $item->toObject();
	    return $result;
	}
	
	public function getItens()
	{
	    return $this->itens;
	}
    public function setItens($value = array())
    {
        $this->itens = $value;
    }
    public function addItem(Shipment $value)
    {
        $this->itens[] = $value;
    }
    public function delItem($index)
    {
    	unset($this->itens[$index]);
    }
    
	
    /**
     * @return number
     */
    public function itemLenght()
    {
        return count($this->itens);
    }
    
    /**
     * retorna item com menor preço entre os itens definidos
     * @return Shipment
     */
    public function getItemByPrecoMin()
    {
        $result = new Shipment();
    
        //loop nos itens
        foreach( $this->getItens() as $item )
        {
            $cond1 = empty($result->getServico()); //nenhum serviço declarado
            $cond2 = ($item->getValor() < $result->getValor()); //valor do item for menor
            if( $cond1 || $cond2 )
            {
                $result = $item;
            }
        }
        
        return $result;
    }
    
    /**
     * retorna item com maior prazo entre os itens definidos
     * @return Shipment
     */
    public function getItemByPrazoMax()
    {
        $result = new Shipment();
        
        foreach( $this->getItens() as $item )
        {
            if( $item->getPrazo() > $result->getPrazo() )
            {
                $result = $item;
            }
        }
         
        return $result;
    }
    
    /**
     * @param string $value
     * @param bool $default_price_min
     * @return Shipment
     */
    public function getItemByService($value, $default_price_min = false)
    {
        //retornar o item do serviço
    	foreach( $this->getItens() as $item )
    	{
    		if( $item->getServico() == $value )
    		{
    			return $item;
    		}
    	}
    	
    	//retornar o item com o menor preço
    	if( $default_price_min === true )
    	{
    	    return $this->getItemByPrecoMin();
    	}
    	
    	//retornar a instancia do model
    	$item = new Shipment();
    	return $item;
    }
    
    /**
     * @param int $value
     * @return Shipment
     */
    public function getItemByPosition($value)
    {
        if( isset($this->itens[$value]) )
        {
            return $this->itens[$value];
        }
         
        $item = new Shipment();
        return $item;
    }
    
    public function orderShipmentItemByPrice()
    {
        $array = $this->getItens();
        usort($array, function($a, $b)
        {
            return $a->getValor() <=> $b->getValor();
        });
        $this->setItens($array);
    }
}