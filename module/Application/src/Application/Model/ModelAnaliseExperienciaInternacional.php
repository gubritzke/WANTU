<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelAnaliseExperienciaInternacional extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_analise_experiencia_internacional';
	
	public $fields = array('id_analise_experiencia_internacional', 'id_analise_de_curriculo', 'experiencia_internacional', 'tipo_de_experiencia', 'tempo_de_duracao', 'pais_experiencia', 'status', 'modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->analise_experiencia_internacional, $adapter);
	}

	public function get($filter=array())
	{
		$alias = 'analiseExperienciaInternacional';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		
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