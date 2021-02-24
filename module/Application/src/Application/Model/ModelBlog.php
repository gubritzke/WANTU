<?php
namespace Application\Model;
use Application\Model\ModelTableGateway;
use Zend\Mvc\Application;

/**
 * @author Deco
 */
class ModelBlog extends ModelTableGateway 
{
	protected $tb = null; 

	public $primary_key = 'id_blog';
	
	public $fields = array('id_blog', 'titulo', 'slug', 'status', 'imagem', 'tags', 'texto', 'criado', 'modificado');
		
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->blog, $adapter);
	}

	public function get($filter=array(), $params = null, $paginacao = false )
	{
		
		$alias = 'blog';
		
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
		
		
		if( $paginacao == true ){
			
			$paginator = new \Zend\Paginator\Adapter\DbSelect($qry, $this->adapter);
			$paginator = new \Zend\Paginator\Paginator($paginator);
			$paginator->setItemCountPerPage( 10 );
			$paginator->setCurrentPageNumber( $_GET['page'] == NULL ? 1 : $_GET['page'] );
			return $paginator;
			
		} else {
			
			return $result;
			
		}
		
	}
	
	public function slugUnique($str, $id=null)
	{
		//remove acentos, espaços e converte para letras minúsculas
		$slug = strtolower(\Application\Classes\Convert::removeEspecialChars($str));
		
		
		$alias = 'blog';
		
		$sql = new \Zend\Db\Sql\Sql($this->adapter);
		$qry = $sql->select();
		$qry->from([$alias => $this->tableName]);
		
		$qry->where(['blog.slug' => $slug]);
		
		if(!empty($id)){
			$qry->where('blog.id_blog != '.$id);
		}
		
		
		$qry->group('id_blog');
		
		$result = $sql->getSqlStringForSqlObject($qry);
		$result = $this->adapter->query($result, $this->adapter::QUERY_MODE_EXECUTE);
		$result = $result->toArray();
		
		//caso haja outro igual, adiciona um incremento numérico
		if( count($result) )
		{
			$slug = explode('-', $slug);
			if( is_numeric(end($slug)) ){
				$new = end($slug) + 1;
				array_pop($slug);
				$slug = implode('-', $slug) . '-' . $new;
			} else {
				$slug = implode('-', $slug) . '-1';
			}
			
			//chama a função recursivamente, informando o novo slug criado
			return $this->slugUnique($slug, $id);
		}
		
		//retorna o slug unico
		return $slug;
	}
	
	public function del($id)
	{
	
		foreach ( $id as $row ) {
				
			parent::delete($this->primary_key.' = '.$row);
				
		}
	
	}
	
}