<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoOrder extends ModelAbstract
{
    /**
     * Código de identificação do pedido
     * @var string
     */
	protected $code;
	
	/**
	 * Canal de vendas onde o pedido foi 'originado' (marketplace)
	 * @var string
	 */
	protected $channel;
	
	/**
	 * Data e hora de criação do pedido
	 * @var string
	 */
	protected $placed_at;
	
	/**
	 * Data e hora de atualização do pedido
	 * @var string
	 */
	protected $updated_at;
	
	/**
	 * Valor total do pedido
	 * @var float
	 */
	protected $total_ordered;
	
	/**
	 * Valor de juros do pedido
	 * @var float
	 */
	protected $interest;
	
	/**
	 * Valor de desconto do pedido
	 * @var float
	 */
	protected $discount;
	
	/**
	 * Método de envio do pedido
	 * @var float
	 */
	protected $shipping_cost;
	
	/**
	 * Método de envio do pedido
	 * @var string
	 */
	protected $shipping_method;
	
	/**
	 * Data estimada de entrega
	 * @var string
	 */
	protected $estimated_delivery;
	
	/**
	 * Data estimada do deslocamento de entrega
	 * @var string
	 */
	protected $estimated_delivery_shift;
	
	/**
	 * Endereço de entrega 
	 * @var ModelAddress
	 */
	protected $shipping_address;
	
	/**
	 * Endereço de cobrança
	 * @var ModelAddress
	 */
	protected $billing_address;
	
	/**
	 * Dados do cliente
	 * @var ModelCustomer
	 */
	protected $customer;
	
	/**
	 * Itens do pedido
	 * @var ModelItem
	 */
	protected $items = array();
	
	/**
	 * Status do pedido
	 * @var ModelStatus
	 */
	protected $status;
	
	/**
	 * Informações de faturas
	 * @var ModelInvoice
	 */
	protected $invoices = array();
	
	/**
	 * Informações de envios
	 * @var ModelShipment
	 */
	protected $shipments = array();
	
	/**
	 * Informações de pagamentos
	 * @var ModelPayment
	 */
	protected $payments = array();
	
	/**
	 * Status de sincronização do pedido. Valores possíveis: "SYNCED", "NOT_SYNCED" e "ERROR"
	 * @var string
	 */
	protected $sync_status;
	
	/**
	 * Informação número do pedido
	 * @var array
	 */
	protected $import_info = array();
	
	/**
	 * Tipo de Calculo do Frete , Exemplo B2WENTREGA
	 * @var string
	 */
	protected $calculation_type;
	
	/**
	 * 
	 * @var array
	 */
	protected $tags = array();
	
	/**
	 * Data da entrega
	 * @var string
	 */
	protected $shipped_date;
	
	/**
	 * Data da aprovação
	 * @var string
	 */
	protected $approved_date;
	
	public function getCode()
	{
		return $this->code;
	}
	public function setCode($value)
	{
		$this->code = $value;
	}

	public function getChannel()
	{
	    return $this->channel;
	}
	public function setChannel($value)
	{
	    $this->channel = $value;
	}

	public function getPlacedAt()
	{
	    return $this->placed_at;
	}
	public function setPlacedAt($value)
	{
	    $this->placed_at = $value;
	}

	public function getUpdatedAt()
	{
	    return $this->updated_at;
	}
	public function setUpdatedAt($value)
	{
	    $this->updated_at = $value;
	}
    
	public function getTotalOrdered()
	{
	    return $this->total_ordered;
	}
	public function setTotalOrdered($value)
	{
	    $this->total_ordered = $value;
	}
    
	public function getInterest()
	{
	    return $this->interest;
	}
	public function setInterest($value)
	{
	    $this->interest = $value;
	}
    
	public function getDiscount()
	{
	    return $this->discount;
	}
	public function setDiscount($value)
	{
	    $this->discount = $value;
	}
    
	public function getShippingCost()
	{
	    return $this->shipping_cost;
	}
	public function setShippingCost($value)
	{
	    $this->shipping_cost = $value;
	}
    
	public function getShippingMethod()
	{
	    return $this->shipping_method;
	}
	public function setShippingMethod($value)
	{
	    $this->shipping_method = $value;
	}
    
	public function getEstimatedDelivery()
	{
	    return $this->estimated_delivery;
	}
	public function setEstimatedDelivery($value)
	{
	    $this->estimated_delivery = $value;
	}
    
	public function getEstimatedDeliveryShift()
	{
	    return $this->estimated_delivery_shift;
	}
	public function setEstimatedDeliveryShift($value)
	{
	    $this->estimated_delivery_shift = $value;
	}
	
	public function getShippingAddress()
	{
	    return $this->shipping_address;
	}
	public function setShippingAddress(ModelAddress $value)
	{
	    $this->shipping_address = $value;
	}
    
	public function getBillingAddress()
	{
	    return $this->billing_address;
	}
	public function setBillingAddress(ModelAddress $value)
	{
	    $this->billing_address = $value;
	}
    
	public function getCustomer()
	{
	    return $this->customer;
	}
	public function setCustomer(ModelCustomer $value)
	{
	    $this->customer = $value;
	}
	
	public function getItems()
	{
	    return $this->items;
	}
	public function addItem(ModelItem $value)
	{
	    $this->items[] = $value;
	}
	
	public function getStatus()
	{
	    return $this->status;
	}
	public function setStatus(ModelStatus $value)
	{
	    $this->status = $value;
	}
	
	public function getInvoices()
	{
	    return $this->invoices;
	}
	public function addInvoice(ModelInvoice $value)
	{
	    $this->invoices[] = $value;
	}
    
	public function getShipments()
	{
	    return $this->shipments;
	}
	public function addShipment(ModelShipment $value)
	{
	    $this->shipments[] = $value;
	}
	
	public function getPayments()
	{
	    return $this->payments;
	}
	public function addPayment(ModelPayment $value)
	{
	    $this->payments[] = $value;
	}
	
	/**
	 * retornar um array com todos os pagamentos
	 * @return array
	 */
	public function getPaymentsAsArray()
	{
	    $array = array();
	    
	    foreach( $this->payments as $payment )
	    {
	        $array[] = $payment->toArray();
	    }
	    
	    return $array;
	}

	public function getSyncStatus()
	{
	    return $this->sync_status;
	}
	public function setSyncStatus($value)
	{
	    $this->sync_status = $value;
	}

	public function getImportInfo()
	{
	    return $this->import_info;
	}
	public function setImportInfo($value)
	{
	    $this->import_info = (array)$value;
	}

	public function getCalculationType()
	{
	    return $this->calculation_type;
	}
	public function setCalculationType($value)
	{
	    $this->calculation_type = $value;
	}

	public function getTags()
	{
	    return $this->tags;
	}
	public function setTags($value)
	{
	    $this->tags = $value;
	}

	public function getShippedDate()
	{
	    return $this->shipped_date;
	}
	public function setShippedDate($value)
	{
	    $this->shipped_date = $value;
	}

	public function getApprovedDate()
	{
	    return $this->approved_date;
	}
	public function setApprovedDate($value)
	{
	    $this->approved_date = $value;
	}
	
	public function populate($array)
	{
	    $array = (array)$array;
	    
	    //loop no array
	    foreach( $array as $key=>$value )
	    {
	        //add shipping address
	        if( $key == "shipping_address" )
	        {
	            $model = new ModelAddress();
                $this->setShippingAddress($model->populate($value));
	        }
	        
	        //add billing address
	        if( $key == "billing_address" )
	        {
	            $model = new ModelAddress();
                $this->setBillingAddress($model->populate($value));
	        }
	        
	        //add items
	        if( $key == "items" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelItem();
	                $this->addItem($model->populate($v));
	            }
	        }
	        
	        //add status
	        if( $key == "status" )
	        {
	            $model = new ModelStatus();
	            $this->setStatus($model->populate($value));
	        }
	        
	        //add invoices
	        if( $key == "invoices" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelInvoice();
	                $this->addInvoice($model->populate($v));
	            }
	        }
	        
	        //add shipments
	        if( $key == "shipments" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelShipment();
	                $this->addShipment($model->populate($v));
	            }
	        }
	        
	        //add payments
	        if( $key == "payments" )
	        {
	            foreach( $value as $v )
	            {
	                $model = new ModelPayment();
	                $this->addPayment($model->populate($v));
	            }
	        }
	    }
	    
	    return parent::populate($array);
	}
	
	/**
	 * retornar array com os todos os ids dos items
	 * @return array
	 */
	public function getItemIds()
	{
	    $array = array();
	    
	    foreach( $this->items as $item )
	    {
	        $array[] = $item->getProductId();
	    }
	    
	    return $array;
	}
	
	/**
	 * retornar item com ID informado
	 * @param string $value
	 * @return ModelItem
	 */
	public function getItemById($value)
	{
	    foreach( $this->items as $item )
	    {
	        if( $item->getProductId() == $value )
	        {
	            return $item;
	        }
	    }
	    
	    return new ModelItem();
	}
	
	/**
	 * retorna o cálculo de dias para a data de entrega
	 * @return int
	 */
	public function getEstimatedDeliveryTime()
	{
	    $dateStart = new \DateTime(date("Y-m-d"));
	    $dateEnd = new \DateTime($this->estimated_delivery);
	    
	    $dateInterval = $dateStart->diff($dateEnd);
	    return (int)$dateInterval->days;
	}
	
	/**
	 * retorna o status do pedido em inteiro
	 *
	 * 1. STATUS_AGUARDANDO_PAGAMENTO
	 * NEW - Pagamento Pendente (SkyHub)
	 * NEW é o status que o pedido recebe quando gerado no marketplace.
	 *
	 * 2. STATUS_PAGAMENTO_REALIZADO
	 * APPROVED - Aprovado (SkyHub)
	 * Approved é o status que o pedido recebe quando o pagamento é aprovado (confirmado).
	 *
	 * 3. STATUS_SEPARACAO
	 * Não existe na SkyHub
	 *
	 * 4. STATUS_POSTADO
	 * SHIPPED - Pedido Enviado (SkyHub)
	 * Shipped é o Status que o pedido recebe quando o lojista envia os dados de NFe e Código de Rastreio.
	 *
	 * 5. STATUS_FINALIZADO
	 * DELIVERED - Completo (entregue) (SkyHub)
	 * Delivery é o status que o pedido recebe quando o pedido foi entregue ao cliente.
	 *
	 * 6. STATUS_CANCELADO
	 * CANCELED - Cancelado (SkyHub)
	 * Cancel é o status que o pedido recebe quando cancelado porque o cliente desistiu da compra ou quando o lojista não tem mais o estoque para atender ao pedido.
	 *
	 * ?. Não existe na Octoplace
	 * SHIPMENT_EXCEPTION - Exceção de Entrega (SkyHub)
	 * Shipment_exception é o status que o pedido recebe quando por algum motivo a entrega não foi realizada.
	 *
	 * @param string $status
	 * @return int
	 */
	public function getOrderStatus()
	{
	    $status = $this->getStatus()->getType();
	    switch( $status )
	    {
	        case "APPROVED":
	            return 2; break;
	
	        case "SHIPPED":
	            return 4; break;
	
	        case "DELIVERED":
	            return 5; break;
	
	        case "CANCELED":
	            return 6; break;
	
	        default:
	            return 1; break;
	    }
	}
	
	/**
	 * retorna o status do pagamento em inteiro
	 *
	 * 0. STATUS_AGUARDANDO_PAGAMENTO
	 * NEW - Pagamento Pendente (SkyHub)
	 * NEW é o status que o pedido recebe quando gerado no marketplace.
	 *
	 * 1. STATUS_PAGAMENTO_REALIZADO
	 * APPROVED - Aprovado (SkyHub)
	 * Approved é o status que o pedido recebe quando o pagamento é aprovado (confirmado).
	 *
	 * 1. STATUS_SEPARACAO
	 * Não existe na SkyHub
	 *
	 * 1. STATUS_POSTADO
	 * SHIPPED - Pedido Enviado (SkyHub)
	 * Shipped é o Status que o pedido recebe quando o lojista envia os dados de NFe e Código de Rastreio.
	 *
	 * 1. STATUS_FINALIZADO
	 * DELIVERED - Completo (entregue) (SkyHub)
	 * Delivery é o status que o pedido recebe quando o pedido foi entregue ao cliente.
	 *
	 * 2. STATUS_CANCELADO
	 * CANCELED - Cancelado (SkyHub)
	 * Cancel é o status que o pedido recebe quando cancelado porque o cliente desistiu da compra ou quando o lojista não tem mais o estoque para atender ao pedido.
	 *
	 * ?. Não existe na Octoplace
	 * SHIPMENT_EXCEPTION - Exceção de Entrega (SkyHub)
	 * Shipment_exception é o status que o pedido recebe quando por algum motivo a entrega não foi realizada.
	 *
	 * @param string $status
	 * @return int
	 */
	public function getPaymentStatus()
	{
	    $status = $this->getStatus()->getType();
	    switch( $status )
	    {
	        case "APPROVED":
	            return 1; break;
	
	        case "SHIPPED":
	            return 1; break;
	
	        case "DELIVERED":
	            return 1; break;
	
	        case "CANCELED":
	            return 2; break;
	
	        default:
	            return 0; break;
	    }
	}
	
}