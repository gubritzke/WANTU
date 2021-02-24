<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelLogin;
use Application\Classes\MailMessage;

class CadastroController extends GlobalController
{
	public function indexAction()
	{
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		//verifica se existe alguma informação na session
		$session = \Naicheframework\Session\Session::get("cadastro", true);
		if( !empty($session) ) $this->view['data'] = (object)$session;
		//echo'<pre>'; print_r($this->view['data']); exit;
		
		//view
		$this->head->setTitle('Cadastro');
		return new ViewModel($this->view);
	}
	
	protected function salvar()
	{
		try
		{
			//params
			$params = $this->params()->fromPost();
			//echo'<pre>'; print_r($params); exit;

			//validar
			\Application\Validate\ValidateUsuario::$tb = $this->tb;
			\Application\Validate\ValidateUsuario::$adapter = $this->adapter;
			$validate = \Application\Validate\ValidateUsuario::cadastro($params);
			if( $validate !== true ) throw new \Exception($validate);
			
			\Naicheframework\Security\NoCSRF::valid('content');
			
			//redirecionamento
			$redirect = !empty($this->params()->fromPost('r')) ? $this->params()->fromPost('r') : '/login';
			
			//nome completo
			$params['nome'] = $params['nome'];
			
			//login
			$params['login'] = $params['email'];
			
			//models
			$modelLogin = new ModelLogin($this->tb, $this->adapter);
			
			//salva na tabela login
			$params['id_login'] = $modelLogin->save($params);

			//envia email notificando o novo cadastro
			$to = $params['email'];
			$replace = array(
					'nome' => $params['nome'],
					'email' => $params['email'],
			);
			$mail = new MailMessage();
			$mail->cadastroSucesso($to, $replace);
			
			//mensagem sucesso
			$this->flashmessenger()->addSuccessMessage('Usuário cadastrado com sucesso!');
			
			//redirect
			return $this->redirect()->toUrl($redirect);
			
		} catch(\Exception $e)
		{
			//recupera os dados para exibir no formulário
			$this->view['data'] = (object)$params;
			
			//mensagem erro
			$message['alert alert-danger'] = $e->getMessage();
			$this->layout()->setVariable('message', $message);
		}
	}
	
}
