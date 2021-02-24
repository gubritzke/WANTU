<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class ShipmentApi extends ModelAbstract
{
    /**
     * lista com as APIs disponíveis
     * @var array
     */
    protected $list = array();
    
    /**
     * definir o GATEWAY que será utilizado
     * @var string
     */
    protected $gateway;
    
    /**
     * configurações de acesso do GATEWAY
     * @var array
     */
    protected $config = array();
    
    /**
     * class para consultar o banco de dados
     * @var \Naicheframework\Api\Request
     */
    protected $database;
    
    public function __construct()
    {
        $this->list = array(
            'correio',
            'mandae',
            'jadlog',
            'melhorenvio',
        );
    }
    
	public function getGateway()
    {
    	return $this->gateway;
    }
    
    public function setGateway($value)
    {
    	$this->gateway = \Naicheframework\Helper\Convert::removeEspecialChars(mb_strtolower($value));
    }
    
    public function getConfig()
    {
    	return $this->config;
    }
    
    public function setConfig($value)
    {
    	$this->config = $value;
    }
    
    public function getDatabase()
    {
        return $this->database;
    }
    
    public function setDatabase(\Naicheframework\Api\Request $value)
    {
        $this->database = $value;
    }
    
    public function setList(array $value)
    {
        $this->list = $value;
    }
    public function getList()
    {
        return $this->list;
    }
    
    /**
     * retornar o nome da class do gateway
     * @return string
     */
    public function getGatewayClass()
    {
        if( !in_array($this->gateway, $this->list) )
        {
            return current($this->list);
        }
        
        return $this->gateway;
    }
    
    /**
     * instanciar a class referente a API
     * @return \Naicheframework\Shopping\Shipment\ShipmentAbstract
     */
    public function factory()
    {
        //instanciar a class referente ao gateway
        $class = "\Naicheframework\Shopping\Shipment\Shipment" . ucfirst($this->getGatewayClass());
        $shipment = new $class($this->config);
        
        if( !empty($this->database) && method_exists($shipment, 'setDatabase') )
        {
            $shipment->setDatabase($this->database);
        }
        
        return $shipment;
    }
}