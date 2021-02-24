<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelAnaliseDeCurriculo extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_analise_de_curriculo';
	
	public $fields = array('id_analise_de_curriculo', 'id_login', 'status','aguardando','data_de_nascimento', 'sexo', 'nome_mae', 'nome_pai', 'estado_civil', 'filhos', 'nacionalidade', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'estado', 'cidade', 'telefone_residencial', 'telefone_celular', 'rg', 'orgao_emissor', 'data_emissao', 'estado_emissor', 'carteira_de_trabalho', 'numero_de_serie',  'possui_cnh', 'e_fumante', 'alguma_deficiencia', 'qual_deficiencia', 'mora_com_o_respnsalvel', 'possui_conta_bancaria', 'possui_cartao_de_credito', 'quantas_pessoas_moram_em_seu_domiciolio', 'quantos_pessoas_trabalham_em_seu_domiciolio', 'como_voce_avalia_seu_convivio', 'seu_domiciolio_e', 'renda_total', 'quantas_compartilham', 'como_voce_conheceu_a_wantu', 'objetivos_profissionais', 'disponibilidade_internacional', 'modificado', 'foto_curriculo', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->analise_de_curriculo, $adapter);
	}

	public function get($filter=array(), $params = null, $paginacao = false )
	{
		
		$alias = 'analiseDeCurriculo';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		
// 		//login usuario
		$qry->join(array('login'=>$this->tb->login),
				"analiseDeCurriculo.id_login = login.id_login",
				['nome'=>'nome'],
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
			$paginator->setItemCountPerPage( 7 );
			$paginator->setCurrentPageNumber( $_GET['page'] == NULL ? 1 : $_GET['page'] );
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