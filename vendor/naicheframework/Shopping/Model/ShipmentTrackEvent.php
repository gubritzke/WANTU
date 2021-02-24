<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class ShipmentTrackEvent extends ModelAbstract
{
    const STATUS_PENDENTE = "Pendente";
    const STATUS_POSTADO = "Postado";
    const STATUS_EMTRANSPORTE = "Em transporte";
    const STATUS_ENTREGUE = "Entregue";
    
    protected $data;
    protected $hora;
    protected $descricao;
    protected $local;
    protected $cidade;
    protected $estado;
    protected $status;
    
	public function getData()
    {
    	return $this->data;
    }
    public function setData($value)
    {
        $this->data = !empty($value) ? date("Y-m-d", strtotime($value)) : date("Y-m-d");
    }
    
    public function getHora()
    {
    	return $this->hora;
    }
    public function setHora($value)
    {
    	$this->hora = $value;
    }
    public function setHoraByDatetime($value)
    {
        $this->hora = !empty($value) ? date("H:i:s", strtotime($value)) : date("H:i:s");
    }
    
    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($value)
    {
        $this->descricao = $value;
    }
    
    public function getLocal()
    {
        return $this->local;
    }
    public function setLocal($value)
    {
        $this->local = $value;
    }
    
    public function getCidade()
    {
        return $this->cidade;
    }
    public function setCidade($value)
    {
        $this->cidade = $value;
    }
    
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($value)
    {
        $this->estado = $value;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($value)
    {
        $this->status = $value;
    }
    public function setStatusByCorreios($value)
    {
        switch( $value )
        {
            case "Objeto entregue ao destinatário": $this->status = self::STATUS_ENTREGUE; break;
            case "Objeto postado": $this->status = self::STATUS_POSTADO; break;
            case "": $this->status = self::STATUS_PENDENTE; break;
            default: $this->status = self::STATUS_EMTRANSPORTE;
        }
    }
    public function setStatusByMandae($value)
    {
        switch( $value )
        {
            case "Entrega realizada": $this->status = self::STATUS_ENTREGUE; break;
            case "Pedido entregue": $this->status = self::STATUS_ENTREGUE; break;
            case "Recebida na Mandaê": $this->status = self::STATUS_POSTADO; break;
            case "": $this->status = self::STATUS_PENDENTE; break;
            default: $this->status = self::STATUS_EMTRANSPORTE;
        }
    }
}