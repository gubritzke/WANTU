<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;

class CartaoController extends GlobalController
{
    public function indexAction()
    {
    	
    	return new ViewModel($this->view);
    }
    
    public function francielihDornelesAction()
    {
    	return new ViewModel($this->view);
    }
    
    public function raquelMenezesAction()
    {
    	return new ViewModel($this->view);
    }
        
}
