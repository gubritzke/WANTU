<?php
namespace Naicheframework\Mktplace\Model\Pluggto;

/**
 * @author: Vitor Deco
 */
class ModelPluggtoPhoto extends ModelAbstract
{
    protected $url;
    protected $name;
    protected $title;
    protected $order;
    protected $external;
    
    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($value)
    {
        $this->url = $value;
    }
    
    public function getName()
    {
        return $this->name;
    }
    public function setName($value)
    {
        $this->name = $value;
    }

    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle($value)
    {
        $this->title = $value;
    }

    public function getOrder()
    {
        return $this->order;
    }
    public function setOrder($value)
    {
        $this->order = $value;
    }

    public function getExternal()
    {
        return $this->external;
    }
    public function setExternal($value)
    {
        $this->external = $value;
    }
}