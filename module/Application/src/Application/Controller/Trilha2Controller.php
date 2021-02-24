<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelLogin;
use Application\Model\ModelPerfilComportamental;

class Trilha2Controller extends GlobalController
{
	
	private function init()
	{
		
		$user = new ModelLogin($this->tb, $this->adapter);
		
		$filter = [];
		$filter['where'] = 'id_login = "'.$this->layout()->me->id_login.'"';
		$getuser = $user->get($filter)->toArray()[0];

		if ( $getuser['perfil_comportamental'] == 'Completo' ) {
			
			return $this->redirect()->toUrl('/trilha2/sucesso');
			
		}
		
 	}
	
	public function sendAction()
	{
		
		$post = $this->params()->fromPost();
		
		
		$model = new ModelPerfilComportamental($this->tb, $this->adapter);
		
		foreach ( $post['option'] as $option ) {
		    
		    //echo '<pre>'; print_r( $option ); exit;
			
			//save 	
			$params['id_login'] = $this->layout()->me->id_login;
			$params['name'] = $option['name'];
			$params['value'] = $option['value'];
			$params['key'] = $option['key'];
			$model->save($params);
			//echo '<pre>'; print_r( $params); exit;

		}
		
		$params['perfil_comportamental'] = "Completo";
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$modellogin->save($params, $this->layout()->me->id_login);
		//redirect
		return $this->redirect()->toUrl('/trilha2/sucesso');
		
		
	}
    
    public function indexAction()
    {
    	
    	$this->init();
    	
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Minha conta'] = 'https://wantu.com.br/minha-conta';
    	$breadcrumb['Como você tende a se comportar nas diversas situações do dia-a-dia?'] = 'https://wantu.com.br/trilha2';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	//echo '<pre>'; print_r($this->layout()->me->id_login); exit;
    	
    	$this->head->setJs('extensions/jquery.perfect.scrollbar/js/init.js');
    	$this->head->setJs('extensions/jquery.perfect.scrollbar/js/perfect-scrollbar.jquery.min.js');
    	
    	return new ViewModel($this->view);
    }
    
    public function sucessoAction()
    {
    	//params
    	$where = "perfilComportamental.id_login = '" . $this->layout()->me->id_login. "'";
    	$model = new ModelPerfilComportamental($this->tb, $this->adapter);
    	$this->view['result'] = $model->get(["expr" => $where]);
    	$this->view['result'] = $model->resultado($this->view['result']->toArray());
    	//echo'<pre>'; print_r($this->view['result']); exit;
    	
    	return new ViewModel($this->view);
    }
}

