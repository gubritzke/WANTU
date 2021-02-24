<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Db\TableGateway\TableGateway;

/**
 * 
 * @author vitor
 * 
 */
class ModelLogin extends ModelTableGateway
{
	/**
	 * @var Object lista de tabelas
	 */
	protected $tb = null;
	
	protected $primary_key = 'id_login';
	
	protected $fields = array('id_login', 'nivel', 'nome', 'imagem', 'email', 'cpf', 'telefone', 'cidade', 'estado', 'nascimento', 'cep', 'endereco', 'numero', 'bairro', 'complemento', 'senha', 'analise_de_curriculo', 'perfil_comportamental', 'aptidoes_profissionais', 'inteligencias_multiplas', 'pontos_fortes', 'competencias', 'file_pdi', 'modificado', 'criado');
	
	protected $required = array('email','senha');
	
	public $paginacao = array();
	
	
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->login, $adapter);
	}
	
	public function get( $filter=array(), $paginacao = false, $getPaginacao = 'page', $nmPage = '20' )
	{
		$alias = "login";
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		$qry->group("login.id_login");
		
		if ( !empty($filter['where']) ) {
			$qry->where( $filter['where'] );
		}
		
		if ( $filter['ordem'] ){
			$qry->order( $filter['ordem'] );
		}
		
		if( !empty($filter['expr']) ){
		    $qry->where($filter['expr']);
		}
		
		$result = $sql->getSqlStringForSqlObject($qry);
		$result = $this->adapter->query($result, 'execute');
		
		if( $paginacao == true ){
			
			$paginator = new \Zend\Paginator\Adapter\DbSelect($qry, $this->adapter);
			$paginator = new \Zend\Paginator\Paginator($paginator);
			$paginator->setItemCountPerPage( $nmPage);
			$paginator->setCurrentPageNumber( $_GET[$getPaginacao] == NULL ? 1 : $_GET[$getPaginacao] );
			
			//echo '<pre>';print_r( $paginator ); exit;
			
			return $paginator;
			
		} else {
			
			return $result;
			
		}
		
	}
	
	public function get2( $filter=array(), $paginacao = false, $getPaginacao = 'page', $nmPage = '5' )
	{
		//plano atual
		$id_planos_usuario = "(SELECT id_planos_usuario FROM wt_planos_usuario AS PU WHERE PU.id_login = login.id_login AND PU.status = 1 ORDER BY PU.criado DESC LIMIT 1)";
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from(['login' => $this->tableName]);
		
		//planos
		$qry->join(array('planos_usuario'=>$this->tb->planos_usuario),
				new \Zend\Db\Sql\Expression("planos_usuario.id_planos_usuario = $id_planos_usuario"),
				['id_plano'=>'id_plano', 'status'=>'status'],
				$qry::JOIN_LEFT);
		
		if ( !empty($filter['where']) ) {
			$qry->where( $filter['where'] );
		}
		
		if ( $filter['ordem'] ){
			$qry->order( $filter['ordem'] );
		}
		
		if( !empty($filter['expr']) ){
			$qry->where($filter['expr']);
		}
		
		$result = $sql->getSqlStringForSqlObject($qry);
		$result = $this->adapter->query($result, 'execute');
		
		if( $paginacao == true ){
			
			$paginator = new \Zend\Paginator\Adapter\DbSelect($qry, $this->adapter);
			$paginator = new \Zend\Paginator\Paginator($paginator);
			$paginator->setItemCountPerPage( $nmPage);
			$paginator->setCurrentPageNumber( $_GET[$getPaginacao] == NULL ? 1 : $_GET[$getPaginacao] );
			
			//echo '<pre>';print_r( $paginator ); exit;
			
			return $paginator;
			
		} else {
			
			return $result;
			
		}
		
	}
	
	public function get3( $filter=array(), $paginacao = false, $getPaginacao = 'page', $nmPage = '5' )
	{
	    $alias = "login";
	    $sql = new \Zend\Db\Sql\Sql($this->adapter);
	    $qry = $sql->select();
	    $qry->from([$alias=>$this->tableName]);
	    $qry->group("login.id_login");
	    
	    $qry->join(array('resultadosFinais'=>$this->tb->resultados_finais),
	        "resultadosFinais.id_login = login.id_login",
	        ['id_resultados_finais','maior_carreira','academica','empreendedora','gerencial','publica','politica','especialista','cp1','cp2','cp3','cp4','cp5','cp6','cp7','cp8','cp9','cp10','cp11','cp12','cp13','cp14','cp15'],
	        $qry::JOIN_LEFT);
	    
	    $qry->join(array('analiseDeCurriculo'=>$this->tb->analise_de_curriculo),
	        "analiseDeCurriculo.id_login = login.id_login",
	        ['aguardando', 'status', 'data_de_nascimento', 'sexo', 'nome_mae', 'nome_pai', 'estado_civil', 'filhos', 'nacionalidade', 'cep_analise'=>'cep', 'endereco_analise'=>'endereco', 'numero_analise'=>'numero', 'complemento_analise'=>'complemento', 'bairro_analise'=>'bairro', 'estado_analise'=>'estado', 'cidade_analise'=>'cidade', 'telefone_residencial', 'telefone_celular', 'rg', 'orgao_emissor', 'data_emissao', 'estado_emissor', 'carteira_de_trabalho', 'numero_de_serie', 'possui_cnh', 'e_fumante', 'alguma_deficiencia', 'qual_deficiencia', 'mora_com_o_respnsalvel', 'possui_conta_bancaria', 'possui_cartao_de_credito', 'quantas_pessoas_moram_em_seu_domiciolio', 'quantos_pessoas_trabalham_em_seu_domiciolio', 'como_voce_avalia_seu_convivio', 'seu_domiciolio_e', 'renda_total', 'quantas_compartilham', 'como_voce_conheceu_a_wantu', 'objetivos_profissionais', 'disponibilidade_internacional', 'foto_curriculo'],
	        $qry::JOIN_LEFT);
	    
	    $qry->join(array('dadosescolares'=>$this->tb->dados_escolares),
	        "dadosescolares.id_login = login.id_login",
	        ['id_dados_escolares', 'id_analise_de_curriculo', 'status_curso'],
	        $qry::JOIN_LEFT);

	    if ( !empty($filter['where']) ) {
	        $qry->where( $filter['where'] );
	    }
	    
	    if ( $filter['ordem'] ){
	        $qry->order( $filter['ordem'] );
	    }
	    
	    if( !empty($filter['expr']) ){
	        $qry->where($filter['expr']);
	    }
	    
	    $result = $sql->getSqlStringForSqlObject($qry);
	    $result = $this->adapter->query($result, 'execute');
	    
	    if( $paginacao == true ){
	        
	        $paginator = new \Zend\Paginator\Adapter\DbSelect($qry, $this->adapter);
	        $paginator = new \Zend\Paginator\Paginator($paginator);
	        $paginator->setItemCountPerPage( $nmPage);
	        $paginator->setCurrentPageNumber( $_GET[$getPaginacao] == NULL ? 1 : $_GET[$getPaginacao] );
	        
	        $this->paginacao = $paginator->getPages();
	        
	        return $paginator;
	        
	    } else {
	        
	        return $result;
	        
	    }
	    
	}
	
	public function changeFields($fields)
	{
		if( !empty($fields['senha']) )
		{
			$fields['senha'] = md5($fields['senha']);
		}
		
		return $fields;
	}
	
	public function getLoginByEmailAndId($id, $email){
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from(['login' => $this->tableName]);
		
		if(empty($id) || empty($email)){
			return null;
		}
		
		$qry->where('id_login = '.$id);
		$qry->where('email = "'.$email.'"');
		
		$result = $sql->getSqlStringForSqlObject($qry);
		$result = $this->adapter->query($result, 'execute');
		return $result->current();
	}
	
	public function save($set, $id=null)
	{
		$set = $this->changeFields($set);
		return parent::save($set, $id);
	}
	
	public function validaUsuario($slug){
		$slug = explode('-', base64_decode($slug));
		
		$retorno = $this->getLoginByEmailAndId($slug[1], $slug[0]);
		
		if(!empty($retorno)){
			return true;
		}else{
			return false;
		}
		
	}
}