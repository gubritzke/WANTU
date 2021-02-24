<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelAptidoesProfissionais extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_aptidoes_profissionais';
	
	public $fields = array('id_aptidoes_profissionais', 'id_login', 'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8', 'p9', 'p10', 'p11', 'p12', 'p13', 'p14', 'p15', 'p16', 'p17', 'p18', 'p19', 'p20', 'p21', 'p22', 'p23', 'p24', 'p25', 'criado', 'modificado');
	
	protected $ancoraPerguntas = array(
		'p3'=>'Empreendedorismo',
		'p12'=>'Empreendedorismo',
		'p18'=>'Empreendedorismo',
		'p4'=>'Dedicacao',
		'p14'=>'Dedicacao',
		'p23'=>'Dedicacao',
		'p5'=>'Desafio',
		'p13'=>'Desafio',
		'p20'=>'Desafio',
		'p6'=>'Estilo',
		'p15'=>'Estilo',
		'p21'=>'Estilo',
		'p7'=>'Internacional',
		'p16'=>'Internacional',
		'p8'=>'Especialista',
		'p9'=>'Gerencial',
		'p10'=>'Autonomia',
		'p11'=>'Seguranca',
		'p19'=>'Seguranca',
	);
	
	
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->aptidoes_profissionais, $adapter);
	}

	public function get($filter=array(), $params = null, $paginacao = false )
	{
		
		$alias = 'aptidoes_profissionais';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		
// 		//login usuario
		$qry->join(array('login'=>$this->tb->login),
				"aptidoes_profissionais.id_login = login.id_login",
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
	
	protected function getAptidaoByIdLogin($id_login){
		$alias = 'aptidoes_profissionais';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		
		$qry->where('aptidoes_profissionais.id_login ='.$id_login);
		
		$result = $sql->getSqlStringForSqlObject($qry);
		$result = $this->adapter->query($result, $this->adapter::QUERY_MODE_EXECUTE);
		
		return $result->current();
	}
	
	public function getResultadoAptidaoProfissional($id_login){
		
		$resultado  = $this->getAptidaoByIdLogin($id_login);
		
		for($i = 1; $i<26; $i++){
			$index = 'p'.$i;
			
			$result = $this->isJson($resultado->$index);
			
			if(empty($result->value)){
				if($result->value != '0'){
					$valor = $resultado->$index;
					$resultado->$index = array('name'=>$this->ancoraPerguntas[$index], 'value'=>$valor);
					$resultado->$index = (object) $resultado->$index;
				}else{
					$resultado->$index = json_decode($resultado->$index);
				}
			}else{
				$resultado->$index = json_decode($resultado->$index);
			}
			
			
		}
		
		
		$respostasAncora = $this->separaPerguntasPorAncora($resultado);
		
		$respostasAncoraPorcentagem = array();
		foreach ($respostasAncora as $key=>$row){
			$respostasAncoraPorcentagem[$key] = $this->calculaPorcentagem($row,20); 
		}
		
		
		return $respostasAncoraPorcentagem;
		
	}
	
	public function tresMaioresPontuacoes($pontuacoes){
		$final = array(0=>0,1=>0,2=>0);
		
		foreach ($pontuacoes as $key=>$row){
			if($row['porcentagem'] > $final[0]['porcentagem']){
				$final[0] = $row;
				$final[0]['ancora'] = $key;
			}elseif($row['porcentagem'] > $final[1]['porcentagem']){
				$final[1] = $row;
				$final[1]['ancora'] = $key;
			}elseif($row['porcentagem'] > $final[2]['porcentagem']){
				$final[2] = $row;
				$final[2]['ancora'] = $key;
			}elseif($row['porcentagem'] >= $final[2]['porcentagem']){
				$chave = sizeof($final);
				$final[$chave] = $row;
				$final[$chave]['ancora'] = $key;
			}
		}
		
		return $final;
	}
	
	protected function calculaPorcentagem($ancora, $maximo){
		$final  = array('pontos'=>0, 'porcentagem'=>0);
		
		foreach ($ancora as $row){
			$final['pontos'] = $final['pontos'] + $row;
			$final['porcentagem'] = ($final['pontos']*100) / $maximo;
			
		}
		
		return $final;
		
		
	}
	
	protected function separaPerguntasPorAncora($resultado){
		$final = array();
		for($i = 1; $i<26; $i++){
			$index = 'p'.$i;
			
			$final[$resultado->$index->name][] =$resultado->$index->value; 
			
		}
		
		return $final;
	}
	
	protected function isJson ($string) {
		// decode the JSON data
		$result = json_decode($string);
		
		// switch and check possible JSON errors
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				$error = ''; // JSON is valid // No error has occurred
				break;
			case JSON_ERROR_DEPTH:
				$error = 'The maximum stack depth has been exceeded.';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Invalid or malformed JSON.';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = 'Control character error, possibly incorrectly encoded.';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
				break;
				// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
				break;
				// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$error = 'One or more recursive references in the value to be encoded.';
				break;
				// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$error = 'One or more NAN or INF values in the value to be encoded.';
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = 'A value of a type that cannot be encoded was given.';
				break;
			default:
				$error = 'Unknown JSON error occured.';
				break;
		}
		
		if ($error !== '') {
			// throw the Exception or exit // or whatever :)
// 			exit($error);
		}
		
		// everything is OK
		return $result;
	}
	
	protected function setPerguntas(){
		 
	}
	
	public static function getPerguntas($indice){
		$perguntas['Gerencial'] = 'Sonho ser diretor geral de uma organização e tomar decisões que vão impactar a vida de muitas pessoas.';
		$perguntas['Especialista'] = 'É mais importante me especializar muito em alguma área específica da minha carreira do que saber um pouquinho de tudo. ';
		$perguntas['Autonomia'] = 'Sonho em trabalhar em uma empresa que me dê flexibilidade e autonomia para desempenhar minha função.';
		$perguntas['Seguranca'] = 'Ao procurar por um emprego prezo por organizações que me propiciem segurança e estabilidade.';
		$perguntas['Empreendedorismo'] = 'Me sinto mais realizado em ter minha própria empresa do que ocupar um alto cargo em uma empresa de terceiros.';
		$perguntas['Dedicacao'] = 'Para ter sucesso em minha carreira preciso estar um lugar que posso colocar a minha capacidade a serviço de outras pessoas.';
		$perguntas['Desafio'] = 'Quanto maiores são os problemas mais me sinto incitado a resolvê-los e é isso que espero como realidade da minha carreira ideal.';
		$perguntas['Estilo'] = 'A carreira perfeita para mim é aquela que me oferece possibilidades de conciliar meus interesses pessoais, familiares e profissionais. ';
		$perguntas['Internacional'] = 'É mais importante para mim ter oportunidade de trabalhar no ambiente internacional do que assumir um alto cargo em uma organização que atue somente em contexto nacional.';
		
		
		return $perguntas[$indice];
	}
	
}