<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelLogin;
use Application\Classes\MailMessage;

class LoginController extends GlobalController
{
	public function indexAction()
	{
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		//view
		$this->head->setTitle('Login');
		return new ViewModel($this->view);
	}
	public function recuperarAction()
	{
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		return new ViewModel($this->view);
		
	}

	protected function recuperarEmail()
	{
		
		$post = $this->params()->fromPost();
		//echo '<pre>'; print_r(); exit;
		
		//seleciona os dados do usuário
		$modelUsuario = new ModelLogin($this->tb, $this->adapter);
		$filter['expr'] = 'email = "' . $post['email'] . '" AND nivel = 0';
		$result = $modelUsuario->get($filter)->current();
		
		if (!empty($result)){
			$mail = new MailMessage();
			
			$replace = array();
			$replace['link'] = base64_encode($result->email.'-'.$result->id_login);
			
			$retorno = $mail->esqueciSenhaCliente($result->email, $replace,null);
			
			//echo '<pre>'; print_r($retorno); exit;
		} else {
			$this->flashMessenger()->addInfoMessage("Email não existente.");
		}
		return new ViewModel($this->view);
		
	}
	
	public function mudaSenhaAction(){
		$post = $this->params()->fromPost();
		
		$login = new ModelLogin($this->tb, $this->adapter);
		$retorno = $login->validaUsuario($get['slug']);
		
		//echo '<ore>'; print_r($post['slug']); exit;
		$post['slugdecode'] = explode('-', base64_decode($post['slug']));
		
		if (empty($params['repetir_senha'])){
			unset($params['senha'], $params['repetir_senha']);
		}
		
		if ($post['senha'] == $post['repetir_senha']){
			
			//salva na tabela
			$model = new ModelLogin($this->tb, $this->adapter);
			$model->save($post, $post['slugdecode'][1]);
			
		}else{
			
			$this->flashMessenger()->addInfoMessage("Senha errada, digite novamente.");
			return $this->redirect()->toUrl('/login/recuperar-senha/?slug='. $post['slug']);
		
		}

		//redirect
		return $this->redirect()->toUrl('/login');
			
		//echo '<pre>'; print_r($post); exit;
	}
	
	public function recuperarSenhaAction()
	{
		if( method_exists($this, $method) ) $this->$method();
		
		$get = $this->params()->fromQuery();
		
		$login = new ModelLogin($this->tb, $this->adapter);
		$retorno = $login->validaUsuario($get['slug']);
		
		if($retorno == false){
			$this->flashMessenger()->addInfoMessage("Token de recuperação invalido");
			return $this->redirect()->toUrl('/');
		}else{
			$this->view['slug'] = $get['slug'];
		}
		
		return new ViewModel($this->view);
		
	}
	
	public function sairAction()
	{
		//destroy session
		$session = new \Zend\Session\Container('Auth');
		$session->getManager()->destroy();
		
		//redirecionamento
		return $this->redirect()->toRoute(null, array('controller'=>'index'));
	}
	
	public function facebookAction()
	{
		try
		{
			//verificar se existe as configurações do facebook
// 			if( empty($this->layout()->config_facebook) )
// 			{
// 				throw new \Exception('Houve um problema ao carregar o Facebook, utilize o login padrão!');
// 			}
			
			//redirecionamento após o login
			$redirect = \Naicheframework\Session\Session::get('r');
			if( empty($redirect) )
			{
				$redirect = $this->params()->fromQuery('r', '/');
				\Naicheframework\Session\Session::set('r', $redirect);
			}
			
			//instancia do SDK
			$fb = new \Naicheframework\Facebook\SDK();
			
			//verificar se retornou algum erro
			if( !empty($this->params()->fromQuery('error')) )
			{
				throw new \Exception('O login foi cancelado ou recusado.');
			}
			
			//caso não retornou o code redireciona para o facebook
			if( empty($this->params()->fromQuery('code')) )
			{
				//redireciona ao facebook
				$url = $fb->loginRedirect($redirect);
				return $this->redirect()->toUrl($url);
			}
			
			//selecionar todos os dados recuperados do facebook
			$params = $fb->get();
			
			//echo '<pre>'; print_r($params); exit;
			
			//forçar o login do usuário
			$loginReturn = self::logarUsuarioDados($params["email"], $this->tb, $this->adapter);
			
			//se encontrou o usuário e fez o login
			if( $loginReturn === true )
			{
				//redirecionar
				\Naicheframework\Session\Session::unset('r');
				return $this->redirect()->toUrl($redirect);
			}
			
			//armazena dados na session
			$data = array();
			$data['id_facebook'] = $params["id"];
			$data['email'] = $params["email"];
			$data['nome'] = $params["name"];
			//$data['sexo'] = (strtolower($params["gender"]) == "male") ? "Masculino" : "Feminino";
			$data['foto_cover'] = isset($params["cover"]["source"]) ? $params["cover"]["source"] : null;
			$data['foto'] = isset($params["picture"]['url']) ? $params["picture"]['url'] : null;
			\Naicheframework\Session\Session::set("cadastro", $data);
			
			//mensagem
			$this->flashMessenger()->addInfoMessage("Complete o seu cadastro para continuar.");
			
			//redirecionar para o cadastro
			return $this->redirect()->toUrl("/cadastro");
			
		} catch( \Exception $e ){
			
			//mensagem de erro
			$this->flashMessenger()->addErrorMessage($e->getMessage());
			
			//redirecionamento
			return $this->redirect()->toRoute(null, ['controller'=>'login'], ['query'=>['r'=>$redirect]]);
		}
	}
	
	protected function logarAdmin()
	{
		//login
		$login = $this->params()->fromPost('login');
		
		//password
		$password = $this->params()->fromPost('senha');
		
		//redirecionamento
		$redirect = !empty($this->params()->fromPost('r')) ? $this->params()->fromPost('r') : '/minha-conta';
		
		try 
		{
			//validar login
			$where = " (login.email = '" . $login . "' OR REPLACE(REPLACE(login.cpf, '.', ''), '-', '') = '" . str_replace(['.','-'], '', $login) . "') ";
			$where .= " AND (login.senha = '" .  md5($password) . "')";
			$where .= " AND (login.nivel = 0)";
			$model = new ModelLogin($this->tb, $this->adapter);
			$response = $model->get(['expr'=>$where])->current();
			
			//echo '<pre>'; print_r($response); exit;
			
			//retorna erro caso inválido
			if( empty($response->id_login) ) throw new \Exception('Dados inválidos.');
			
			self::logarUsuarioDados($response->email, $this->tb, $this->adapter);
			
		} catch(\Exception $e){
			
			$this->flashMessenger()->addErrorMessage($e->getMessage());
		}
		
		//redirecionamento
		return $this->redirect()->toUrl($redirect);
	}
	
	public static function logarUsuarioDados($login, $tb, $adapter)
	{
	    //validar login
	    $where = "(login.email = '" . $login . "')";
	    $model = new ModelLogin($tb, $adapter);
	    $response = $model->get(['expr'=>$where])->current();
	    
	    //set session
	    $session = new \Zend\Session\Container('Auth');
	    $session->offsetSet('me', $response);
	    
	    return !empty($response->id_login) ? true : false;
	}
}