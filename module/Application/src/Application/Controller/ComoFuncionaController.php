<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;

class ComoFuncionaController extends GlobalController
{
    public function indexAction()
    {
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Como Funciona'] = 'https://wantu.com.br/como-funciona';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	//echo '<pre>'; print_r($breadcrumb); exit;
    	
    	return new ViewModel($this->view);
    	
    }
        
}
