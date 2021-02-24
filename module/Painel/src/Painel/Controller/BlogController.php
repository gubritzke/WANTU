<?php
namespace Painel\Controller;

use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;
use Application\Model\ModelBlog;

class BlogController extends GlobalController
{
	private function init()
	{
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		$this->head->setTitle('Painel');
		$this->head->addTextEditor();
		$this->head->addSelect2();
		
	}
	
    public function indexAction()
    { 
    	$this->init();
    	
    	$filter = array();
    	$filter['expr'] = 'status != "Excluido"';
    	$model = new ModelBlog($this->tb, $this->adapter);
    	$this->view['result'] = $model->get($filter, null, true);
    	
        return new ViewModel($this->view);
    }
    
    public function formularioAction()
    {
    	$this->init();
    	
    	return new ViewModel($this->view);
    }
    
    public function editarAction()
    {
    	$this->init();
    	
    	//definir id do login
    	$this->view['id_blog'] = $id_login = $this->params('id');
    	$id_blog = $this->view['id_blog'];
    	
    	$filter = array();
    	$filter['expr'] = 'id_blog = "' . $id_blog . '"';
    	$model = new ModelBlog($this->tb, $this->adapter);
    	$this->view['result'] = $model->get($filter, null, false)->current();
    	
    	//view
    	$view = new ViewModel($this->view);
    	$view->setTemplate('painel/blog/formulario');
    	return $view;
    }
    
    protected function salvar()
    {
    	try
    	{
    		//id
    		$id = $this->params('id');
    		
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		
    		//upload
    		$file = $_FILES['imagem'];
    		if( !empty($file['name']) )
    		{
    			$path = "/assets/application/uploads/";
    			$upload = new \Naicheframework\Upload\Upload($path);
    			$upload->setExtensions(["jpg","png","gif"])->file($file, "blog");
    			if( !empty($upload->getError()) ) throw new \Exception("Erro no upload da imagem!");
    			$params['imagem'] = $upload->getFilenameCurrent();
    		}
    		
    		
    		
//     		crop
//     		$crop = new \Naicheframework\Upload\SimpleImage($_SERVER['DOCUMENT_ROOT'] . $path . $params['imagem']);
//     		$crop->fitToWidth("600");
//          echo'<pre>'; print_r($params); exit;
    		
    		//salva no banco
    		$model = new ModelBlog($this->tb, $this->adapter);
    		$params['slug'] = $model->slugUnique($params['titulo'],$id);
    		$params['id_blog'] = $model->save($params, $id);
    		
    		
    		
    		//redirect
    		return $this->redirect()->toUrl('/painel/blog');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function deletarAction()
    {
    	
    	try
    	{
    		
    		//id  $get = $this->params()->fromQuery();
    		$id = $this->params('id');
    		
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		$params['status'] = "Excluido";
    		
    		//salva no banco
    		$model = new ModelBlog($this->tb, $this->adapter);
    		$params['id_blog'] = $model->save($params, $id);
    		
    		//redirect
    		return $this->redirect()->toUrl('/painel/blog');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
}
