<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;

class PoliticaDePrivacidadeController extends GlobalController
{
    public function indexAction()
    {
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Política de Privacidade'] = 'https://wantu.com.br/politica-de-privacidade';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	//echo '<pre>'; print_r($breadcrumb); exit;
    	
    	return new ViewModel($this->view);
    	
    }
        
}
