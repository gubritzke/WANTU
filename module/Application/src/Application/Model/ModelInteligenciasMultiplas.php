<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelInteligenciasMultiplas extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_inteligencias_multiplas';
	
	public $fields = array('id_inteligencias_multiplas', 'id_login', 'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8', 'p9', 'p10', 'p11', 'p12', 'p13', 'p14', 'p15', 'p16', 'p17', 'p18', 'p19','modificado', 'criado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->inteligencias_multiplas, $adapter);
	}

	public function get($filter=array())
	{
		$alias = 'inteligencias_multiplas';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias=>$this->tableName]);
		
		//login usuario
		$qry->join(array('login'=>$this->tb->login),
				"inteligencias_multiplas.id_login = login.id_login",
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
		return $result;
	}
	
	public function del($id)
	{
	
		foreach ( $id as $row ) {
				
			parent::delete($this->primary_key.' = '.$row);
				
		}
	
	}
	
	protected function setPerguntas(){
		
	}
	
	public static function getPerguntas($indice){
		$perguntas['Logico-matematico'] = 'Tenho facilidade para fazer cálculos de cabeça e aprendo mais facilmente disciplinas da área de exatas.';
		$perguntas['Linguistica'] = 'Adoro ler e aprender outras línguas é relativamente fácil para mim.';
		$perguntas['Musical'] = 'Aprendo qualquer coisa com mais facilidade ouvindo músicas que curto.';
		$perguntas['Naturalista'] = 'Gosto de fazer uns rolês com uma mochila nas costas, de acampar, ou de simplesmente caminhar observando a natureza e os animais.';
		$perguntas['Existencial'] = 'Costumo passar um tempo sozinho meditando ou pensando sobre questões importantes da vida e da sociedade.';
		$perguntas['Interpessoal'] = 'Sou o tipo de pessoa a quem os outros recorrem pedindo conselhos, seja no trabalho, na família ou amigos. ';
		$perguntas['Espacial'] = 'Aprender sempre é mais fácil quando estou vendo diagramas, imagens, tabelas, desenhos, etc..';
		$perguntas['Corporal'] = 'Rendo mais no trampo e na facul quando pratico minhas atividades físicas semanais.';
		$perguntas['Intrapessoal'] = 'Gosto de ficar na minha e de fazer as coisas do meu jeito, olhando mais para dentro de mim do que para fora.';
		
		return $perguntas[$indice];
	}
	
	public function tresMaioresPontuacoes( $result )
	{
		$questions = array();
		
		for( $i=1; $i<=19; $i++ )
		{
			$key = "p$i";
			$question = json_decode($result->$key);
			
			if( empty($questions[$question->name]) )
			{
				$questions[$question->name] = 0;
			}
			
			$questions[$question->name] += $question->value;
		}
		
		//ordenar do maior pro menor
		arsort($questions);
		
		//loop nas questoes
		$last = 0;
		$i = 0;
		foreach( $questions as $key=>$value )
		{
			$i++;
			
			if( $i == 3 )
			{
				$last = $value;
			}
			
			if( $i > 3 )
			{
				if( $last != $value )
				{
					unset($questions[$key]);
				}
			}
			
		}
		
		return $questions;
	}
	
	public function todasPontuacoes( $result )
	{
	    $questions = array();
	    
	    for( $i=1; $i<=19; $i++ )
	    {
	        $key = "p$i";
	        $question = json_decode($result->$key);
	        
	        if( empty($questions[$question->name]) )
	        {
	            $questions[$question->name] = 0;
	        }
	        
	        $questions[$question->name] += $question->value;
	    }
	    
	    //ordenar do maior pro menor
	    arsort($questions);
	    
	    //loop nas questoes
	    $last = 0;
	    $i = 0;
	    foreach( $questions as $key=>$value )
	    {
	        $i++;
	        
	        if( $i == 9 )
	        {
	            $last = $value;
	        }
	        
	        if( $i > 9 )
	        {
	            if( $last != $value )
	            {
	                unset($questions[$key]);
	            }
	        }
	        
	    }
	    
	    return $questions;
	}
	
}