<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelBlog;
use Application\Model\ModelLogin;
use Application\Model\ModelPlanosUsuario;

class IndexController extends GlobalController
{
	private function init()
	{
		$filter = array();
		$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login. '"';
		$model = new ModelLogin($this->tb, $this->adapter);
		$this->view['data_user'] = $model->get($filter)->current();
	}
	
    public function indexAction()
    {
    	$this->init();
    	
    	//SELECIONA O PLANO
    	$filter = array();
    	$filter['expr'] = 'id_login = "'. $this->layout()->me->id_login.'"';
    	$filter['expr'] .=' AND status = "1"';
    	$filter['order'] = 'id_plano DESC';
    	$model = new ModelPlanosUsuario($this->tb, $this->adapter);
    	$plano_user = $model->get($filter)->current();
    	
    	if ($plano_user->id_plano == '1' ){
    		$this->view['plano1'] = 1;
    	}
    	if ($plano_user->id_plano == '2' ){
    		$this->view['plano2'] = 1;
    	}
    	if ($plano_user->id_plano == '3' ){
    		$this->view['plano3'] = 1;
    	}
    	
    	$filter = array();
    	$filter['expr'] = 'status = "Ativo"';
    	$model = new ModelBlog($this->tb, $this->adapter);
    	$this->view['result'] = $model->get($filter, null, true);
    	
    	$this->head->addCarousel();
    	$this->head->setJs('carousel.js');
    	//echo '<pre>'; print_r($this->layout()->me->id_login); exit;
    	    	
    	return new ViewModel($this->view);
    	
    }
        
}
