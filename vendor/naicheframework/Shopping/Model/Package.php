<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class Package extends ModelAbstract
{
	protected $peso;
    protected $altura;
    protected $comprimento;
    protected $largura;

    /**
     * valor de todos os itens do pacote
     * @var number
     */
    protected $valor;
    
    /**
     * tipo do pacote pode ser Caixa ou Cilindro
     * @var string
     */
    protected $tipo;

    /**
     * código de rastreio
     * @var string
     */
    protected $rastreio;
    
    /**
     * ids dos itens que estão no pacote
     * @var array
     */
    protected $ids = array();
    
    /**
     * @var ShipmentMultiple
     */
    protected $shipments;
    
    /**
     * informações da etiqueta
     * @var ShipmentEtiqueta
     */
    protected $shipment_etiqueta;
    
    public function getValor()
    {
    	return $this->valor;
    }
    public function setValor($value)
    {
    	$this->valor = $value;
    }
    
    public function getPeso()
    {
    	return $this->peso;
    }
    public function getPesoGramas()
    {
        return $this->peso * 1000;
    }
    public function setPeso($value)
    {
    	//peso em gramas, faz o cálculo para transformar 150 em 0.150
    	$value = (int)$value / 1000;
    	$this->peso = !empty($value) ? $value : 0.5;
    }
    
    public function getAltura()
    {
    	$value = ($this->altura < 15) ? 15 : $this->altura;
    	return $value;
    }
    public function setAltura($value)
    {
    	$this->altura = $value;
    }
    
    public function getComprimento()
    {
        $value = ($this->comprimento < 15) ? 15 : $this->comprimento;
    	return $value;
    }
    public function setComprimento($value)
    {
    	$this->comprimento = $value;
    }
    
    public function getLargura()
    {
        $value = ($this->largura < 15) ? 15 : $this->largura;
    	return $value;
    }
    public function setLargura($value)
    {
        $this->largura = $value;
    }
	
    public function getTipo()
    {
        return $this->tipo;
    }
    public function setTipo($value)
    {
        $this->tipo = $value;
    }
    
    public function getRastreio()
    {
    	return $this->rastreio;
    }
    public function setRastreio($value)
    {
    	$this->rastreio = $value;
    }
    
    public function getId()
    {
    	return $this->ids;
    }
    public function addId($value)
    {
    	$this->ids[] = (int)$value;
    }
    public function countIds()
    {
    	return count($this->ids);
    }
    
    public function getSomaDimensoes()
    {
        return $this->getComprimento() + $this->getLargura() + $this->getAltura();
    }
    
    /**
     * @return ShipmentMultiple
     */
    public function getShipments()
    {
        if( empty($this->shipments) )
        {
            $this->shipments = new ShipmentMultiple();
        }
        
    	return $this->shipments;
    }
    public function setShipments(ShipmentMultiple $shipmentMultiple)
    {
    	$this->shipments = $shipmentMultiple;
    }
    public function countShipments()
    {
        return $this->getShipments()->itemLenght();
    }
    
    public function orderShipmentItemByPrice()
    {
        $this->getShipments()->orderShipmentItemByPrice();
    }
    
    /**
     * definir informações da etiqueta
     * @return ShipmentEtiqueta
     */
    public function getShipmentEtiqueta()
    {
        return $this->shipment_etiqueta;
    }
    public function setShipmentEtiqueta(ShipmentEtiqueta $shipmentEtiqueta)
    {
        $this->shipment_etiqueta = $shipmentEtiqueta;
    }
}