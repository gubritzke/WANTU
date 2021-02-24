<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelAnaliseExperienciaProfissional extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_analise_experiencia_profissional';
	
	public $fields = array('id_analise_experiencia_profissional', 'id_analise_de_curriculo', 'experiencia_profissional', 'tipo_da_experiencia', 'nome_da_empresa', 'cargo', 'data_de_inicio_empresarial', 'data_de_termino_empresarial', 'trabalho_atual', 'regime_de_contrato', 'resumo_das_atividades', 'status', 'modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->analise_experiencia_profissional, $adapter);
	}
	
	public function get($filter=array())
	{
		$alias = 'analiseExperienciaProfissional';
		
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
	
	/**
	 * salva
	 */
	public function setAnaliseExperienciaProdissional($post, $id=null){
		$result = array(
				'error' => 0,
				'data' => $post,
				'msg' => null,
		);
		

		try{
			
			$result['data'][$this->getPrimaryKey()] = $this->save($post, $id);
			
		} catch(\Exception $e){
			$result['error'] = 1;
			$result['msg'] = $e->getMessage();
			$result['error_results'] = $this->getErrorResults();
		}
		//view
		return $result;
	}
	
}