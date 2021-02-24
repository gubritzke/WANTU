<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelPontosFortes extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_pontos_fortes';
	
	public $fields = array('id_pontos_fortes', 'id_login', 'value', 'key', 'modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->pontos_fortes, $adapter);
	}

	public function get($filter=array(), $params = null, $paginacao = false )
	{
		
		$alias = 'pontos_fortes';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		
		//login usuario
		$qry->join(array('login'=>$this->tb->login),
				"pontos_fortes.id_login = login.id_login",
				['nome'=>'nome', 'pontos_fortes'=>'pontos_fortes'],
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
		
		if( $paginacao == true ){
			
			$paginator = new \Zend\Paginator\Adapter\DbSelect($qry, $this->adapter);
			$paginator = new \Zend\Paginator\Paginator($paginator);
			$paginator->setItemCountPerPage( 4 );
			$paginator->setCurrentPageNumber( $_GET['page'] == NULL ? 1 : $_GET['page'] );
			
			//echo '<pre>';print_r( $paginator ); exit;
			
			return $paginator;
			
		} else {
			
			return $result;
			
		}
		
	}
	
	public function del($id)
	{
	
		foreach ( $id as $row ) {
				
			parent::delete($this->primary_key.' = '.$row);
				
		}
	
	}
	
}