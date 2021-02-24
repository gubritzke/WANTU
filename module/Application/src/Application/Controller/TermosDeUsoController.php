<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;

class TermosDeUsoController extends GlobalController
{
    public function indexAction()
    {
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Termos de uso'] = 'https://wantu.com.br/termos-de-uso';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	//echo '<pre>'; print_r($breadcrumb); exit;
    	
    	return new ViewModel($this->view);
    	
    }
        
}
