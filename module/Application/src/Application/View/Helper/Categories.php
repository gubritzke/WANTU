<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Application\Model\ModelCategoria;

class Categories extends AbstractHelper
{
	/**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;
    
    public function __construct(\Zend\ServiceManager\ServiceManager $sm)
    {
        $this->serviceManager = $sm;
    }
    
    public function __invoke()
    {
    	$tb = $this->serviceManager->get('tb');
    	$adapter = $this->serviceManager->get('db');

    	$filter['expr'] = "id_parent = 0";
    	$model = new ModelCategoria($tb, $adapter);
    	$categorias = $model->get($filter)->toArray();
    	$categorias = array_column($categorias, 'categoria', 'slug_categoria');
    	
    	return $categorias;
    }
}