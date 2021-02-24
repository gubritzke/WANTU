<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelPosGraduacao extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_pos_graduacao';
	
	public $fields = array('id_pos_graduacao', 'id_analise_de_curriculo', 'possui_pos_graduacao', 'tipo_pos', 'estado_da_instituicao_pos', 'instituicao_de_ensino_pos', 'nome_da_instituicao_pos', 'status_curso_pos', 'previsao_de_formatura_pos_andamento', 'periodo_andamento_pos', 'duracao_do_curso_andamento_pos', 'ano_inicio_pos_concluido', 'conclusao_pos_concluido', 'status', 'modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->pos_graduacao, $adapter);
	}

	public function get($filter=array())
	{
		$alias = 'posgraduacao';
		
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