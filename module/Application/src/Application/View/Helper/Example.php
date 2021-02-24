<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;

class Example extends AbstractHelper
{
    protected $request;

    public function __construct()
    {
        $this->request = 'rosk!';
    }

    public function __invoke()
    {
        return $this->request;
    }
}