<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class ShipmentEtiqueta extends ModelAbstract
{
    protected $id;
    protected $plp;
    protected $api;
    protected $tipo;
    protected $valor;
    protected $prazo;
    protected $rastreio;
    protected $nfe;
    protected $data_criado;
    protected $data_coleta;
    
	public function getId()
    {
    	return $this->id;
    }
    public function setId($value)
    {
    	$this->id = $value;
    }
    
    public function getPlp()
    {
        return $this->plp;
    }
    public function setPlp($value)
    {
        $this->plp = $value;
    }
    
    public function getApi()
    {
        return $this->api;
    }
    public function setApi($value)
    {
        $this->api = $value;
    }

    public function getTipo()
    {
        return $this->tipo;
    }
    public function setTipo($value)
    {
        $this->tipo = $value;
    }

    public function getValor()
    {
        return $this->valor;
    }
    public function setValor($value)
    {
        $this->valor = $value;
    }

    public function getPrazo()
    {
        return $this->prazo;
    }
    public function setPrazo($value)
    {
        $this->prazo = $value;
    }

    public function getRastreio()
    {
        return $this->rastreio;
    }
    public function setRastreio($value)
    {
        $this->rastreio = $value;
    }

    public function getNfe()
    {
        return $this->nfe;
    }
    public function setNfe($value)
    {
        $this->nfe = $value;
    }

    public function getDataCriado()
    {
        return $this->data_criado;
    }
    public function setDataCriado($value)
    {
        $this->data_criado = $value;
    }
    
    public function getDataColeta()
    {
        return $this->data_coleta;
    }
    public function setDataColeta($value)
    {
        $this->data_coleta = $value;
    }
}