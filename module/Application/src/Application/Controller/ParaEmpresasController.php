<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelContato;
use Application\Model\ModelParaEmpresas;
use Application\Classes\MailMessage;

class ParaEmpresasController extends GlobalController
{
    public function indexAction()
    {
    	
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Contato'] = 'https://wantu.com.br/contato';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	$this->view['states'] = \Naicheframework\Helper\Constants::getStates();
    	
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
            $validate = \Application\Validate\ValidateParaEmpresa::paraEmpresa($params);
            if( $validate !== true ) throw new \Exception($validate);
            
            //echo '<pre>'; print_r($params); exit;
            
            $model = new ModelParaEmpresas($this->tb, $this->adapter);
            
            //salva no banco
            $model->save($params);
            
            //enviar email notificando
            $replace = array(
                'nome' => $params['nome'], 
                'sobrenome' => $params['sobrenome'], 
                'email' => $params['email'], 
                'telefone' => $params['telefone'], 
                'empresa' => $params['empresa'], 
                'colaboradores' => $params['colaboradores'], 
                'gestao_competencias' => $params['gestao_competencias'], 
                'competencias_utilizadas' => $params['competencias_utilizadas'], 
                'estado' => $params['estado'], 
                'area_de_atuacao' => $params['area_de_atuacao'], 
           );
            
            //echo '<pre>'; print_r($replace); exit;
            $mail = new MailMessage();
            $mail->contatoParaEmpresa('gustavo.britzke@naiche.com.br', $replace);
            
            //redirect
            //mensagem sucesso
            $this->flashmessenger()->addSuccessMessage('Contato enviado com sucesso.');
            return $this->redirect()->toUrl('/para-empresas');
            
        } catch(\Exception $e)
        {
            $this->layout()->setVariable('message', array('alert' => $e->getMessage()));
            $this->view['data'] = (object)$params;
        }
    }
        
}
