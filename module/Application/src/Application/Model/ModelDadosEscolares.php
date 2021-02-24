<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelDadosEscolares extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_dados_escolares';
	public $fields = array('id_dados_escolares', 'id_analise_de_curriculo', 'id_login', 'possui_graduacao', 'status_curso', 'estado_da_instituicao_andamento', 'instituicao_de_ensino_andamento', 'nome_do_curso_andamento', 'tipo_do_curso_andamento', 'periodo_andamento', 'duracao_do_curso_andamento', 'horario_andamento', 'previsao_de_formatura_andamento', 'disponibilidade_estagio_andamento', 'coeficiente_de_rendimento_andamento', 'estado_da_instituicao_concluido', 'instituicao_de_ensino_concluido', 'nome_do_curso_concluido', 'tipo_do_curso_concluido', 'duracao_do_curso_concluido', 'horario_concluido', 'conclusao_de_curso_concluido', 'disponibilidade_estagio_concluido', 'coeficiente_de_rendimento_concluido', 'status', 'modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb; 
		parent::__construct($this->tb->dados_escolares, $adapter);
	}

	public function get($filter=array())
	{
		$alias = 'dadosescolares';
		
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