<?php
namespace Naicheframework\Shopping\Shipment;
use Naicheframework\Shopping\Model\Package;
use Naicheframework\Shopping\Model\Sender;
use Naicheframework\Shopping\Model\Recipient;
use Naicheframework\Shopping\Model\Order;
use Naicheframework\Shopping\Model\ShipmentMultiple;
use Naicheframework\Shopping\Model\ShipmentRule;
use Naicheframework\Shopping\Model\OrderMultiple;
use Naicheframework\Shopping\Model\ShipmentTrack;
use Naicheframework\Shopping\Model\ShipmentEtiqueta;

/**
 * @NAICHE | Vitor Deco
 */
abstract class ShipmentAbstract
{
	const ENTREGA_SUPERRAPIDO = "Super Rápido";
	const ENTREGA_RAPIDO = "Rápido";
	const ENTREGA_ECONOMICO = "Econômico";
	
	/**
	 * tipo de envio utilizado
	 * @var string
	 */
	private $shipment_type;
	
	/**
	 * nome do serviço de envio utilizado
	 * @var string
	 */
	private $shipment_name;
	
	/**
	 * definir ambiente
	 * @var bool
	 */
	protected $is_sandbox = false;
		
	public function __construct($shipment_type, $shipment_name)
	{
		$this->shipment_type = $shipment_type;
		$this->shipment_name = $shipment_name;
	}
	
	public function getShipmentType()
	{
		return $this->shipment_type;
	}
	
	public function getShipmentName()
	{
	    return $this->shipment_name;
	}
	
	public function getSandbox()
	{
	    return $this->is_sandbox;
	}
	public function setSandbox($bool)
	{
		$this->is_sandbox = (bool)$bool;
		return $this;
	}
	
	/**
	 * @param Package $package
	 * @param Sender $sender
	 * @param Recipient $recipient
	 * @return ShipmentMultiple
	 */
	public abstract function calcularFrete(Package $package, Sender $sender, Recipient $recipient);
	
	/**
	 * @param Order $order
	 * @return ShipmentEtiqueta
	 */
	public abstract function solicitarEtiqueta(Order $order);
	
	/**
	 * @param Order $order
	 * @return Order
	 */
	public abstract function imprimirEtiqueta(Order $order);
    
	/**
	 * @param string $code
	 * @return ShipmentTrack
	 */
	public abstract function rastrearPedido($code);
	
	/**
	 * @param string $code
	 * @return string
	 */
	public abstract function getLinkRastreio($code = null);
}