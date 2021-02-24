<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelLogin;
use Application\Model\ModelCompetencias;

class Trilha6Controller extends GlobalController
{
	
	private function init()
	{
		
		$user = new ModelLogin($this->tb, $this->adapter);
		
		$filter = [];
		$filter['where'] = 'id_login = "'.$this->layout()->me->id_login.'"';
		$getuser = $user->get($filter)->toArray()[0];
		
		if ( $getuser['competencias'] == 'Completo' ) {
			
			return $this->redirect()->toUrl('/trilha6/sucesso');
			
		}
		
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		
 	}
	
 	protected function salvar()
	{
		
	    try
	    {
	        
    		$params = $this->params()->fromPost();
    		
    		//validar
    		$validate = \Application\Validate\ValidateCompetencias::competencias($params);
    		if( $validate !== true ) throw new \Exception($validate);
    		
    		$params['competencias'] = "Completo";
    		$modellogin = new ModelLogin($this->tb, $this->adapter);
    		$modellogin->save($params, $this->layout()->me->id_login);
    		
    		$model = new ModelCompetencias($this->tb, $this->adapter);
    		
    		$params['id_login'] = $this->layout()->me->id_login;
    		
    		//echo '<pre>'; print_r($params); exit;
    		$model->save($params);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha6/sucesso');
    		
     	} catch(\Exception $e)
     	{
     	    $this->layout()->setVariable('message', array('alert' => $e->getMessage()));
     	    $this->view['data'] = (object)$params;
     	}
    		
		
	}
    
    public function indexAction()
    {
    	
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Minha conta'] = 'https://wantu.com.br/minha-conta';
    	$breadcrumb['Quais são suas principais competências?'] = 'https://wantu.com.br/trilha6';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	
    	$this->init();
    	
    	//echo '<pre>'; print_r($this->layout()->me->id_login); exit;
    	$this->head->setJs('extensions/jquery.perfect.scrollbar/js/init.js');
    	$this->head->setJs('extensions/jquery.perfect.scrollbar/js/perfect-scrollbar.jquery.min.js');
    	
    	return new ViewModel($this->view);
    }
    
    public function sucessoAction()
    {
    	//params
    	//$where = "perfilComportamental.id_login = '" . $this->layout()->me->id_login. "'";
    	//$model = new ModelPerfilComportamental($this->tb, $this->adapter);
    	//$this->view['result'] = $model->get(["expr" => $where]);
    	//$this->view['result'] = $model->resultado($this->view['result']->toArray());
    	//echo'<pre>'; print_r($this->view['result']); exit;
    	
    	return new ViewModel($this->view);
    }
}

