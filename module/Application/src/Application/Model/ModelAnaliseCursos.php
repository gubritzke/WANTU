<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelAnaliseCursos extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_analise_cursos';
	
	public $fields = array('id_analise_cursos', 'id_analise_de_curriculo', 'possui_cursos', 'tipo_de_curso', 'nome_do_curso', 'nivel_do_curso', 'carga_horaria', 'conclusao_curso', 'status', 'modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->analise_cursos, $adapter);
	}

	public function get($filter=array())
	{
		
		$alias = 'analiseCursos';
		
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