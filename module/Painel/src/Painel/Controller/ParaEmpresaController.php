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
use Application\Model\ModelParaEmpresas;

class ParaEmpresaController extends GlobalController
{
    public function indexAction()
    { 
    	$filter = array();
    	$model = new ModelParaEmpresas($this->tb, $this->adapter);
    	$this->view['result'] = $model->get($filter, null, true);
    	
    	$this->head->setJs("helpers/accordion.js");
    	
    	$this->head->setTitle(Painel);
    	
        return new ViewModel($this->view);
    }
    
    public function detalheAction()
    {
        //die('çasdsad');
        $get = $this->params()->fromQuery();
        
        //echo '<pre>'; print_r($get); exit;
        $filter = array();
        $filter['expr'] = '	id_para_empresas = "' . $get['id']. '"';
        $model = new ModelParaEmpresas($this->tb, $this->adapter);
        $this->view['result'] = $model->get($filter, null, false)->current();
        
        //echo '<pre>'; print_r($this->view['result']->current()); exit;
        
        return new ViewModel($this->view);
    }
    
}
