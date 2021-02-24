<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelPerfilComportamental extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_perfil_comportamental';
	
	public $fields = array('id_perfil_comportamental', 'id_login', 'name', 'key', 'value', 'modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->perfil_comportamental, $adapter);
	}

	public function get($filter=array(), $params = null, $paginacao = false )
	{
		
		$alias = 'perfilComportamental';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		
		//login usuario
		$qry->join(array('login'=>$this->tb->login),
				"perfilComportamental.id_login = login.id_login",
				['nome'=>'nome', 'perfil_comportamental'=>'perfil_comportamental'],
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
	
	public function resultado($result)
	{
		
		//Conta comunicador
		$comunicador = array_filter($result, function ($var) {
			return ($var['value'] == 1);
		});
		
		$comunicador = (count($comunicador) * 0.2) + 0.4;

		//Conta analista
		$analista = array_filter($result, function ($var) {
			return ($var['value'] == 2);
		});
			
		$analista = (count($analista) * 0.2) + 0.6;
			
		//Conta Planejador
		$planejador = array_filter($result, function ($var) {
			return ($var['value'] == 3);
		});
			
		$planejador = (count($planejador) * 0.2);

		//Conta executor
		$executor = array_filter($result, function ($var) {
			return ($var['value'] == 4);
		});
			
		$executor = (count($executor) * 0.2);
			
		//Total das contas
		$total_count = $comunicador + $analista + $planejador + $executor;
		
		//Contas das porcentagem
		$comunicador_porcentagem = $comunicador / $total_count * 100;
		
		$analiista_porcentagem = $analista / $total_count * 100;
		
		$planejador_porcentagem = $planejador / $total_count * 100;
		
		$executor_porcentagem = $executor / $total_count * 100;
		
		//Total das porcentagens
		$total_porcentagem = $comunicador_porcentagem + $analiista_porcentagem + $planejador_porcentagem + $executor_porcentagem;
		
		//echo'<pre>'; print_r($total_porcentagem); exit;
		
		//comunicador
		if ( $comunicador_porcentagem < 20) {
			$comunicador_porcentagem_cor = 'red';
		} else if ( $comunicador_porcentagem > 20 && $comunicador_porcentagem < 25 ){
			$comunicador_porcentagem_cor = 'yellow';
		} else {
			$comunicador_porcentagem_cor = 'green';
		}
		
		//analista
		if ( $analiista_porcentagem < 20) {
			$analiista_porcentagem_cor = 'red';
		} else if ( $analiista_porcentagem > 20 && $analiista_porcentagem < 25 ){
			$analiista_porcentagem_cor = 'yellow';
		} else {
			$analiista_porcentagem_cor = 'green';
		}
		
		//planejador
		if ( $planejador_porcentagem < 20) {
			$planejador_porcentagem_cor = 'red';
		} else if ( $planejador_porcentagem > 20 && $planejador_porcentagem < 25 ){
			$planejador_porcentagem_cor = 'yellow';
		} else {
			$planejador_porcentagem_cor = 'green';
		}
		
		//executor
		if ( $executor_porcentagem < 20) {
			$executor_porcentagem_cor = 'red';
		} else if ( $executor_porcentagem > 20 && $executor_porcentagem < 25 ){
			$executor_porcentagem_cor = 'yellow';
		} else {
			$executor_porcentagem_cor = 'green';
		}
		
		return array(
			'comunicador' => number_format($comunicador_porcentagem, 2),
			'comunicador_cor' => $comunicador_porcentagem_cor,
				
			'analiista' =>  number_format($analiista_porcentagem, 2),
			'analiista_cor' => $analiista_porcentagem_cor,
				
			'planejador' => number_format($planejador_porcentagem, 2),
			'planejador_cor' => $planejador_porcentagem_cor,
				
			'executor' => number_format($executor_porcentagem, 2),
			'executor_cor' => $executor_porcentagem_cor,
				
		);
	}
	
}