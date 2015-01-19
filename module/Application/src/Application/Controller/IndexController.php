<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction(){
    	$service = $this->getServiceLocator()->get('cong_viec');
        return array(
            'congViecs' => $service->congViecMois(),
            'baoCaos' => $service->baoCaoMois(),
            'sapHetHans' => $service->sapHetHans(),
            'treHans' => $service->treHans(),
        );
    }

    public function appInfoAction(){
    	
    }
}
