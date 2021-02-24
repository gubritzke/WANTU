<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelBlog;

class BlogController extends GlobalController
{
	private function init()
	{
		$filter = array();
		$filter['expr'] = 'status = "Ativo"';
		$model = new ModelBlog($this->tb, $this->adapter);
		$this->view['result'] = $model->get($filter, null, true);
	}
	
    public function indexAction()
    {
    	
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Blog'] = 'https://wantu.com.br/blog';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	$filter = array();
    	$filter['expr'] = 'status = "Ativo"';
    	$model = new ModelBlog($this->tb, $this->adapter);
    	$this->view['result'] = $model->get($filter, null, true);
    	    	
    	return new ViewModel($this->view);
    }
    public function detalheAction()
    {
    	//definir id do login
    	$this->view['slug'] = $id_login = $this->params('slug');
    	$slug = $this->view['slug'];
    	
    	$filter = array();
//     	$filter['expr'] = 'status = "Ativo"';
    	$filter['expr'] = 'slug = "' . $slug . '"';
    	$model = new ModelBlog($this->tb, $this->adapter);
    	$this->view['result'] = $model->get($filter, null, false)->current();
    	
    	//echo '<pre>'; print_r($this->view['result']); exit;
    	
    	
    	return new ViewModel($this->view);
    }
        
}
