<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelLogin;
use Application\Model\ModelInteligenciasMultiplas;

class Trilha4Controller extends GlobalController
{
	
	private function init()
	{
		
		$user = new ModelLogin($this->tb, $this->adapter);
		
		$filter = [];
		$filter['where'] = 'id_login = "'.$this->layout()->me->id_login.'"';
		$getuser = $user->get($filter)->toArray()[0];
		
		if ( $getuser['inteligencias_multiplas'] == 'Completo' ) {
			
			return $this->redirect()->toUrl('/trilha4/sucesso');
			
		}
		
	}
	
	public function indexAction()
	{

		$this->init();
		
		$breadcrumb = [];
		$breadcrumb['Inicio'] = 'https://wantu.com.br';
		$breadcrumb['Minha conta'] = 'https://wantu.com.br/minha-conta';
		$breadcrumb['Como você aprende melhor?'] = 'https://wantu.com.br/trilha4?pg=1';
		$this->layout()->setVariable('breadcrumb', $breadcrumb);
		
		$get  = $this->params()->fromQuery();
		$post = $this->params()->fromPost();
		
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
			
			if ($next > 18)
			{
				// salva na tabela login
				$params['inteligencias_multiplas'] = "Completo";
				$modellogin = new ModelLogin($this->tb, $this->adapter);
				$modellogin->save($params, $this->layout()->me->id_login);
			}
			
			//instancia model aptidoes profissionais
			$model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
			
			//select na inteligencia
			$filter = [];
			$filter['expr'] = 'inteligencias_multiplas.id_login = "'.$params['id_login'].'"';
			$get = current($model->get( $filter )->toArray());
			$id = $get['id_inteligencias_multiplas'];
			
			//echo '<pre>'; print_r($post); exit;
			
			//salva no banco
			$save = $model->save( $post, $id);
			$params['id_login'] = $save;
			
			//echo '<pre>'; print_r($next); exit;
			
			//echo '<pre>'; print_r($id); exit;
			if ($next == 2){
				$post['p19'] = 0;
			}
			
			//redirect
			if (!empty($empate))
			{
				return $this->redirect()->toUrl('/trilha4/sucesso');
			}
			
			if ($next > 18)
			{
				
				$id_login = $this->layout()->me->id_login;
				
				//select na inteligencia
				$where = "inteligencias_multiplas.id_login = '$id_login'";
				$model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
				$result = $model->get( ['expr'=>$where] )->current();
				$result = $model->tresMaioresPontuacoes($result);
				$conta = array_keys($result);
				
				
				
				if (count($conta) > 3)
 				{
 					//echo '<pre>'; print_r($conta); exit;
 					return $this->redirect()->toUrl('/trilha4/empate');
 				} else {
 					//die('lala');
					return $this->redirect()->toUrl('/trilha4/sucesso');
 				}
				
			} else {
				return $this->redirect()->toUrl('/trilha4?pg='.$next);
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
		$caminho_pg = $_SERVER['DOCUMENT_ROOT'].'/../module/Application/view/application/trilha4/partials/';
		$caminho_pg = $caminho_pg.'passo'.$pg.'.phtml';
		
		if ( file_exists( $caminho_pg) ) {
			
			return 'application/trilha4/partials/passo'.$pg.'.phtml';
			
		} else {
			
			return 'application/trilha4/index.phtml';
			
		}
		
	}
	
	public function sucessoAction()
	{
		$id_login = $this->layout()->me->id_login;
		
		//select na inteligencia
		$where = "inteligencias_multiplas.id_login = '$id_login'";
		$model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
		$result = $model->get( ['expr'=>$where] )->current();
		$result = $model->tresMaioresPontuacoes($result);
		
		//echo '<pre>'; print_r($result); exit;
		
		return new ViewModel($this->view);
		
	}
	
	public function empateAction()
	{
		$id_login = $this->layout()->me->id_login;
		
		//select na inteligencia
		$where = "inteligencias_multiplas.id_login = '$id_login'";
		$model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
		$result = $model->get( ['expr'=>$where] )->current();
		$result = $model->tresMaioresPontuacoes($result);
		//echo '<pre>'; print_r($result); exit;
		$result = array_keys($result);
		
		//echo '<pre>'; print_r($result); exit;
		
		$novo = array();
		for ($i=2; $i < sizeof($result); $i++){
			$novo[] = $result[$i];
		}
		
		$this->view['empate'] = $novo;
		
		//echo '<pre>'; print_r($novo); exit;
		
		//echo '<pre>'; print_r($this->view['empate']); exit;
		
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
	
}
