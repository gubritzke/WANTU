<?php
namespace Application\Model;
use Zend\Db\TableGateway\TableGateway;

/**
 * @NAICHE - Deco
 */
class ModelTableGateway{
	
    /**
     * Table Gateway
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway = null;

    /**
     * Database adapter
     * @var \Zend\Db\Zend\Db\Adapter\AdapterInterface
     */
    protected $adapter = null;

    /**
     * Table Name
     * @var string
     */
    protected $tableName = null;

    /**
     * Primary key
     * @var int
     */
    protected $primary_key = null;

    /**
     * Fields list
     * @var array
     */
    protected $fields = array();
    
    /**
     * Fields required
     * @var array
     */
    protected $required = array();
    
    /**
     * Fields errors in array
     * @var array
     */
    protected $error_result = array();
    
    /**
     * define os valores das variaveis globais da class
     * @param	string $tableName
     * @param	string $adapter
     * @return	void
     */
    public function __construct($tableName, $adapter) 
    {
        $this->tableName = $tableName;
        $this->adapter = $adapter;
        $this->tableGateway = new TableGateway($tableName, $adapter);
    }
    
    /**
     * retorna o array de erros
     * @return array
     */
    public function getErrorResults()
    {
    	return $this->error_result;
    }
    
    /**
     * retorna todos os campos obrigatórios
     * @return array
     */
    public function getRequired()
    {
    	return $this->required;
    }
    
	/**
	 * retorna a coluna que foi definida como chave primária
	 * @return number
	 */
    public function getPrimaryKey()
    {
    	return $this->primary_key;
    }
    
    /**
     * retorna o table gateway
     * @return \Zend\Db\TableGateway\TableGateway
     */
    public function getTableGateway()
    {
    	return $this->tableGateway;
    }
    
    /**
     * verifica se há erros
     * @return boolean
     */
    public function isValidFields()
    {
    	return (count($this->error_result)) ? false : true;
    }
    
    /**
     * filtra todos os dados, retornando apenas os que foram definidos
     * @param array $fields
     * @param array $data
     * @return array
     */
    public function filter($fields, $data)
    {
    	$filter = array();
    	foreach( $data as $k => $v ) 
    	{
    		if( in_array($k, $fields) )
    		{
    			$filter[$k] = $v;
    		}
    	}
    
    	return $filter;
    }
    
    /**
     * valida campos, se todos os campos estiverem preenchidos
     * @param array $required
     * @param array $data
     * @return this
     */
    public function validate($required, $data)
    {
    	$this->error_result = array();
    	foreach( $required as $field ) 
    	{
    		if( !array_key_exists($field, $data) )
    		{
    			$this->error_result[$field] = 'Campo obrigatório';
    		}
    	}
    	return $this;
    }
    
    /**
     * retorna o ResultSet selecionando tudo no banco
     * @return ResultSet
     */
    public function fetchAll()
    {
    	return $this->tableGateway->select();
    }
    
    /**
     * insere ou atualiza no banco de dados
     * @param array $set
     * @param string|int $id
     * @return int
     */
    public function save($set, $id=null)
    {
    	if( !empty($id) )
    	{
    		$set['modificado'] = date('Y-m-d H:i:s');
    		$set = $this->filter($this->fields, $set);
    		
    		$this->tableGateway->update($set, $this->primary_key . " = '" . $id . "'");
    		return $id;
    		
    	} else {
    		$set['criado'] = date('Y-m-d H:i:s');
    		$set = $this->filter($this->fields, $set);
    		
    		$this->tableGateway->insert($set);
    		return $this->tableGateway->lastInsertValue;
    	}
    }
    
    /**
     * atualiza no banco de dados
     * @param array $set
     * @param string $where
     */
    public function update($set, $where)
    {
    	$set['modificado'] = date('Y-m-d H:i:s');
    	$set = $this->filter($this->fields, $set);
    
    	return $this->tableGateway->update($set, $where);
    }
    
    /**
     * deleta no banco de dados
     * @param string|int $where
     * @return int
     */
    public function delete($where)
    {
    	if( is_numeric($where) )
    	{
    		return $this->tableGateway->delete($this->primary_key . " = '" . $where . "'");
    	
    	} else {
    		return $this->tableGateway->delete($where);
    	}
    }
}