<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class OrderMultiple extends ModelAbstract
{
    /**
     * desconto do pedido
     * @var number
     */
	protected $desconto;
	
	/**
	 * adicional do pedido
	 * @var number
	 */
	protected $adicional;
	
	/**
	 * comissão em porcentagem da octoplace
	 * @var int
	 */
	protected $comissao;
	
	/**
	 * código do gateway de pagamento da octoplace
	 * @var string
	 */
	protected $gateway_code;
	
	/**
	 * @var Recipient
	 */
	protected $comprador;

	/**
	 * nome que será exibido na fatura do cartão de 5 a 22 caracteres
	 * @var string
	 */
	protected $nome_na_fatura;
	
	/**
	 * @var Order
	 */
	protected $order = array();
	
	/**
	 * regras de entrega
	 * @var ShipmentRule
	 */
	protected $shipment_rules = array();
	
	public function getDesconto()
	{
	    return $this->desconto;
	}
	
	public function setDesconto($value)
	{
	    $this->desconto = $value;
	}
    
	public function getAdicional()
	{
	    return $this->adicional;
	}
	
	public function setAdicional($value)
	{
	    $this->adicional = $value;
	}
	
	public function hasAdicional()
	{
	    return !empty($this->adicional) ? true : false;
	}

	public function getComissao()
	{
	    return $this->comissao;
	}
	
	public function setComissao($value)
	{
	    $this->comissao = $value;
	}
	
	public function hasComissao()
	{
	    return !empty($this->comissao) ? true : false;
	}
	
	public function getGatewayCode()
	{
	    return $this->gateway_code;
	}
	
	public function setGatewayCode($value)
	{
	    $this->gateway_code = $value;
	}
	
	public function hasGatewayCode()
	{
	    return !empty($this->gateway_code) ? true : false;
	}
	
	public function getComprador()
	{
	    return $this->comprador;
	}
	
	public function setComprador(Recipient $value)
	{
	    $this->comprador = $value;
	}
	
	public function getNomeNaFatura()
	{
	    return !empty($this->nome_na_fatura) ? $this->nome_na_fatura : "octoplace";
	}
	
	public function setNomeNaFatura($value)
	{
	    $this->nome_na_fatura = $value;
	}
	
	/**
	 * criar um novo grupo
	 * @param Order $order
	 */
	public function addOrder(Order $order)
	{
		$this->order[] = $order;
	}
	
	/**
	 * @return Order
	 */
	public function getOrder()
	{
		return $this->order;
	}
	
	/**
	 * quantidade de pedidos
	 * @return int
	 */
	public function countOrder()
	{
		return count($this->order);
	}
	
	/**
	 * quantidade de pacotes
	 * @return int
	 */
	public function countPackage()
	{
		$count = 0;
		
		foreach( $this->order as $order )
		{
			$count += $order->countPackage();
		}
		
		return $count;
	}
	
	/**
	 * @param string $value
	 * @return Order
	 */
	public function getOrderByGroup($value)
	{
		foreach( $this->order as $order )
		{
			if( $order->getGroup() == $value )
			{
				return $order;
			}
		}
		
		$order = new Order();
		return $order;
	}
	
	/**
	 * @return number
	 */
	public function getItemDesconto()
	{
	    $desconto = 0;
	
	    foreach( $this->getOrder() as $order )
	    {
	        foreach( $order->getItem() as $item )
	        {
	            $desconto += $item->getDesconto();
	        }
	    }
	     
	    return $desconto;
	}
	
	/**
	 * somar o desconto do pedido com o desconto dos itens
	 * @return number
	 */
	public function getDescontoTotal()
	{
	    return $this->getDesconto() + $this->getItemDesconto();
	}
	
    /**
	 * @param string $value
	 * @return Item
	 */
	public function getItemById($value)
	{
		foreach( $this->getOrder() as $order )
		{
    		foreach( $order->getItem() as $item )
    		{
    			if( $item->getId() == $value )
    			{
    				return $item;
    			}
    		}
		}
		
		$item = new Item();
		return $item;
	}
	
	/**
	 * calcular a soma total de todos os itens de todos os pedidos
	 * @return float
	 */
	public function getTotal()
	{
	    //soma todos os valores
	    $total = 0;
	
	    //loop em todos os pedidos
	    foreach( $this->getOrder() as $order )
	    {
	        $total += $order->getTotal();
	    }
	
	    return $total;
	}
	
	/**
	 * retornar o maior peso entre os itens adicionados
	 * @return number
	 */
	public function getPesoMax()
	{
	    //peso final
	    $peso_final = 0;
	    
	    //loop em todos os pedidos
	    foreach( $this->getOrder() as $order )
	    {
    	    //loop em todos os itens do pedido
    	    foreach( $order->getItem() as $item )
    	    {
    	        //verificar se o peso do item é maior que o peso final
    	        if( $item->getPeso() > $peso_final )
    	        {
    	            $peso_final = $item->getPeso();
    	        }
    	    }
	    }
	    
	    return $peso_final;
	}
	
	public function getShipmentRules()
	{
	    return $this->shipment_rules;
	}
	public function addShipmentRule( ShipmentRule $value )
	{
	    $this->shipment_rules[] = $value;
	}
	public function checkShipmentRules()
	{
	    return count($this->shipment_rules);
	}
	
	public function checkFreeShipment()
	{
	    $return = true;
	    
	    //loop nos pedidos
	    foreach( $this->getOrder() as $order )
	    {
	        //loop nos pacotes
	        foreach( $order->getPackage() as $package )
	        {
	            $shipmentItem = $package->getShipments()->getItemByPosition(0);
	            
	            if( (int)$shipmentItem->getValor() > 0 )
	            {
	                $return = false;
	            }
	        }
	    }
	    
	    return $return;
	}
	
	public function checkShipmentConvencional()
	{
	    $services = array();
	    
	    //loop nos pedidos
	    foreach( $this->getOrder() as $order )
	    {
	        //loop nos pacotes
	        foreach( $order->getPackage() as $package )
	        {
	            $shipmentItem = $package->getShipments()->getItemByPrecoMin();
	            $services[] = $shipmentItem->getServico();
	        }
	    }
	    
	    return (count(array_unique($services)) == 1) ? true : false;
	}
	
	public function orderShipmentItemByPrice()
	{
	    foreach( $this->getOrder() as $order )
	    {
	        $order->orderShipmentItemByPrice();
	    }
	}
	
}