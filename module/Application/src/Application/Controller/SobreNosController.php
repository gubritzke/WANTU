<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelPlanos;

class SobreNosController extends GlobalController
{
    public function indexAction()
    {
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Sobre'] = 'https://wantu.com.br/sobre';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	//echo '<pre>'; print_r($breadcrumb); exit;
    	
    	return new ViewModel($this->view);
    	
    }
        
}
