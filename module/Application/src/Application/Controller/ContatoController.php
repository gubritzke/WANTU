<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelContato;
use Application\Classes\MailMessage;

class ContatoController extends GlobalController
{
    public function indexAction()
    {
    	
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Contato'] = 'https://wantu.com.br/contato';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	//call actions
    	$method = $this->params()->fromPost('method');
    	if( method_exists($this, $method) ) $this->$method();
    	
    	return new ViewModel($this->view);
    	
    }
    
    protected function salvar()
    {
    	try
    	{
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		
    		//validar
    		$validate = \Application\Validate\ValidateContato::contato($params);
    		if( $validate !== true ) throw new \Exception($validate);
    		
    		//echo '<pre>'; print_r($params); exit;
    		
    		$model = new ModelContato($this->tb, $this->adapter);
    		
    		//salva no banco
    		$model->save($params);
    		
    		//enviar email notificando
    		$replace = array(
    		    'nome' => $params['nome'], 
    		    'email' => $params['email'], 
    		    'telefone' => $params['telefone'], 
    		    'assunto' => $params['assunto'], 
    		    'motivo_contato' => $params['motivo_contato'], 
    		    'mensagem' => $params['mensagem'],
    		);
    		
    		//echo '<pre>'; print_r($replace); exit;
    		$mail = new MailMessage();
    		$mail->contato('gustavo.britzke@naiche.com.br', $replace);
    		
    		//redirect
    		//mensagem sucesso
    		$this->flashmessenger()->addSuccessMessage('Contato enviado com sucesso.');
    		return $this->redirect()->toUrl('/contato');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
        
}
