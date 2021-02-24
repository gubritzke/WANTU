<?php
namespace Naicheframework\Xml;

class Xml
{
    protected $xml;
    
	public function __construct($data)
	{
        //definir o xml \SimpleXMLElement
        $this->xml = new \SimpleXMLElement($data);
	}
    
    public function getXml()
    {
        return $this->xml->asXML();
    }
    
    public function addChild($name, $value=null, $namespace=null)
    {
        return $this->xml->addChild($name, $value, $namespace);
    }
	
	public function save($filename)
	{
 	    $xml = $this->xml->asXML();
 	    $response = file_put_contents($filename, $xml);
 	    return $response;
	}
}