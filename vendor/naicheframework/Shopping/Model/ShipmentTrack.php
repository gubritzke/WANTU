<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class ShipmentTrack extends ModelAbstract
{
    /**
     * etiqueta ou código de referência para o rastreio
     * @var string
     */
    protected $codigo;
    
    /**
     * array de eventos
     * @var ShipmentTrackEvent[]
     */
    protected $eventos = array();
    
	public function getCodigo()
    {
    	return $this->codigo;
    }
    public function setCodigo($value)
    {
        $this->codigo = $value;
    }
    
    public function getEventos()
    {
        return $this->eventos;
    }
    public function addEvento(ShipmentTrackEvent $value)
    {
        $this->eventos[] = $value;
    }
    
    /**
     * recuperar o último status registrado nos eventos
     * @return string
     */
    public function getStatus()
    {
        //selecionar último evento
        $evento = current($this->eventos);
        
        //retornar status
        return $evento->getStatus();
    }
}