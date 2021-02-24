<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Painel for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Painel\Controller;

use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;
use Application\Model\ModelContato;

class ContatoController extends GlobalController
{
    public function indexAction()
    { 
    	
    	
    	$filter = array();
    	$model = new ModelContato($this->tb, $this->adapter);
    	$this->view['result'] = $model->get($filter, null, true);
    	
    	$this->head->setJs("helpers/accordion.js");
    	
    	$this->head->setTitle(Painel);
    	
        return new ViewModel($this->view);
    }
    
}
