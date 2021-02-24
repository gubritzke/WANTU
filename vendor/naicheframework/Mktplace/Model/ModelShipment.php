<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
class ModelShipment extends ModelAbstract
{
    /**
     * código da entrega
     * @var string
     */
    protected $code;
    
    /**
     * itens na entrega
     * @var ModelItem
     */
    protected $items = array();
    
    /**
     * informações do envio
     * @var ModelTrack
     */
    protected $track;
    
    /**
     * multipla informações do envio
     * @var ModelTrack
     */
    protected $tracks = array();
    
    public function getCode()
    {
        return $this->code;
    }
    public function setCode($value)
    {
        $this->code = $value;
    }
    
    public function getItems()
    {
        return $this->items;
    }
    public function addItem(ModelItem $value)
    {
        $this->items[] = $value;
    }
    
    public function getTrack()
    {
        return $this->track;
    }
    public function setTrack(ModelTrack $value)
    {
        $this->track = $value;
    }

    public function getTracks()
    {
        return $this->tracks;
    }
    public function addTrack(ModelTrack $value)
    {
        $this->tracks[] = $value;
    }
    
    public function populate($array)
    {
        //loop no array
        foreach( $array as $key=>$value )
        {
            //add items
            if( $key == "items" )
            {
                foreach( $value as $v )
                {
                    $model = new ModelItem();
                    $this->addItem($model->populate($v));
                }
            }
            
            //add tracks
            if( $key == "tracks" )
            {
                foreach( $value as $v )
                {
                    $model = new ModelTrack();
                    $this->addTrack($model->populate($v));
                }
            }
        }
         
        return parent::populate($array);
    }
}