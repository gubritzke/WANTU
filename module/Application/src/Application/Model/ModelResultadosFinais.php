<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelResultadosFinais extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_resultados_finais';
	
	public $fields = array('id_resultados_finais', 'id_login', 'maior_carreira', 'academica', 'empreendedora', 'gerencial', 'publica', 'politica', 'especialista', 'cp1', 'cp2', 'cp3', 'cp4', 'cp5', 'cp6', 'cp7', 'cp8', 'cp9', 'cp10', 'cp11', 'cp12', 'cp13', 'cp14', 'cp15', 'modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->resultados_finais, $adapter);
	}

	public function get($filter=array())
	{
		$alias = 'resultadosFinais';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		$qry->group("login.id_login");
		
		$qry->join(array('login'=>$this->tb->login),
		    "resultadosFinais.id_login = login.id_login",
		    $qry::SQL_STAR,
		    $qry::JOIN_LEFT);
		
		if( !empty($filter[$this->primary_key]) ){
			$qry->where($alias . '.' . $this->primary_key . ' = "' . $filter[$this->primary_key] . '"');
		}
		
		if( !empty($filter['expr']) ){
			$qry->where($filter['expr']);
		}
		
		if( !empty($filter['limit']) ){
			$qry->limit($filter['limit']);
		}
		
		if( !empty($filter['order']) ){
			$qry->order($filter['order']);
		}
		
		$result = $sql->getSqlStringForSqlObject($qry);
		$result = $this->adapter->query($result, $this->adapter::QUERY_MODE_EXECUTE);
		return $result;
	}
	
	public function del($id)
	{
	
		foreach ( $id as $row ) {
				
			parent::delete($this->primary_key.' = '.$row);
				
		}
	
	}
	
}