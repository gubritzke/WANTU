<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelAptidoesProfissionais;
use Application\Model\ModelLogin;

class Trilha3Controller extends GlobalController
{
	
    public function indexAction()
    {
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Minha conta'] = 'https://wantu.com.br/minha-conta';
    	$breadcrumb['Quais carreiras te atraem mais?'] = 'https://wantu.com.br/trilha3?pg=1';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	
    	$user = new ModelLogin($this->tb, $this->adapter);
    	
    	$filter = [];
    	$filter['where'] = 'id_login = "'.$this->layout()->me->id_login.'"';
    	$get = $user->get($filter)->toArray()[0];
    	
    	if ( $get['aptidoes_profissionais'] == 'Completo' ) {
    		
    		return $this->redirect()->toUrl('/trilha3/sucesso');
    		
    	}
    	
    	$get  = $this->params()->fromQuery();
    	$post = $this->params()->fromPost();
    	
    	$this->head->setJs('/assets/application/js/rangeslider/nouislider.min.js', true);
    	
    	//next and prev
    	$this->view['next'] = $get['pg'] + 1;
    	$this->view['prev'] = $get['pg'] - 1;
    	
    	//atualizar
    	if ( $post ) 
    	{
    		$this->save();
    	}
    	
    	//view conforme a pagina chamada
    	$pg = $this->passoAtual();
    	$view = new ViewModel( $this->view );
     	$teste = $view->setTemplate( $pg );
    	return $view;
    	
    }
    
    private function save()
    {
    	
    	$post = $this->params()->fromPost();
    	$get  = $this->params()->fromQuery();
    	
    	$next = $post['next'];
    	$prev = $post['prev'];
    	$empate = $post['empate'];
    	
    	try
    	{
    		
    		$params['id_login'] = $this->layout()->me->id_login;
    		$post['id_login'] = $params['id_login'];
    		//echo '<pre>'; print_r($params['id_login']); exit;
    		
    		if (empty($post['p'.$get['pg']]) && $this->layout()->routes['action'] != 'empate')
    		{
    			throw new \Exception('Selecione uma das opções.');
    			
    		} elseif ( $this->layout()->routes['action'] == 'empate' && empty($post['opt'])) {
    			
    			throw new \Exception('Selecione uma das opções.');
    			
    		}
    		
    		if ($next > 24)
    		{
    		// salva na tabela login
    		$params['aptidoes_profissionais'] = "Completo";
    		$modellogin = new ModelLogin($this->tb, $this->adapter);
    		$modellogin->save($params, $this->layout()->me->id_login);
    		}
    		
    		//instancia model aptidoes profissionais
    		$model = new ModelAptidoesProfissionais($this->tb, $this->adapter);
    		
    		//select na aptidoes
    		$filter = [];
    		$filter['expr'] = 'aptidoes_profissionais.id_login = "'.$params['id_login'].'"';
    		$get = current($model->get( $filter )->toArray());
    		$id = $get['id_aptidoes_profissionais'];
    		
    		//echo '<pre>'; print_r($id); exit;
    		if ($next == 2){
    			$post['p25'] = 0;
    		}
    		
    		//echo '<pre>'; print_r($post); exit;
    		
    		//salva no banco
    		$save = $model->save( $post, $id);
    		$params['id_login'] = $save;
    		
    		//echo '<pre>'; print_r($next); exit;
    		
    		
    		//redirect
    		if (!empty($empate))
    		{
    			return $this->redirect()->toUrl('/trilha3/sucesso');
    		}
    		
    		if ($next > 24)
    		{
    			//conta se existe empate
    			$id_user = $this->layout()->me->id_login;
    			$aptidao = new ModelAptidoesProfissionais($this->tb, $this->adapter);
    			$this->view['result'] = $aptidao->getResultadoAptidaoProfissional($id_user);
    			$this->view['tres']= $aptidao->tresMaioresPontuacoes($this->view['result']);
    			$conta = count($this->view['tres']);
    			
    			if ($conta > 3)
    			{
    				return $this->redirect()->toUrl('/trilha3/empate');
    			} else {	
	    			return $this->redirect()->toUrl('/trilha3/sucesso');
    			}
	    			
    		} else {
    			return $this->redirect()->toUrl('/trilha3?pg='.$next.'#anc-aqui');
    		}
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    	
    	
    }
    
    protected function passoAtual()
    {
    	
    	$get = $this->params()->fromQuery();
    	
    	//validacao se existe a pagina solicitada
    	$pg = strip_tags( $get['pg'] );
    	$caminho_pg = $_SERVER['DOCUMENT_ROOT'].'/../module/Application/view/application/trilha3/partials/';
    	$caminho_pg = $caminho_pg.'passo'.$pg.'.phtml';
    	
    	if ( file_exists( $caminho_pg) ) {
    		
    		return 'application/trilha3/partials/passo'.$pg.'.phtml';
    		
    	} else {
    		
    		return 'application/trilha3/index.phtml';
    		
    	}
    	
    }
    
    public function empateAction()
    {
    	$id = $this->layout()->me->id_login;
    	
    	$aptidao = new ModelAptidoesProfissionais($this->tb, $this->adapter);
    	$this->view['result'] = $aptidao->getResultadoAptidaoProfissional($id);
    	$this->view['tres']= $aptidao->tresMaioresPontuacoes($this->view['result']);
		
    	//echo '<pre>'; print_r($this->view['tres']); exit;
    	
    	$result = ($this->view['tres']);
    	
    	
    	$novo = array();
    	for ($i=2; $i < sizeof($result); $i++){
    		$novo[] = $result[$i];
    	}
    	
    	$this->view['empate'] = $novo;
    	
    	$post = $this->params()->fromPost();
    	//atualizar
    	if ( $post )
    	{
    		$this->save();
    	}
    	//echo '<pre>'; print_r($novo); exit;
    	//echo '<pre>'; print_r($this->view['tres']); exit;
    	
    	return new ViewModel($this->view);
    }
    
    public function sucessoAction()
    {
    	
    	return new ViewModel($this->view);

    }
        
}
