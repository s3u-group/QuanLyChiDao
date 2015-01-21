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
            'sapHetHanTheoDois' => $service->sapHetHanTheoDois(),
            'treHanTheoDois' => $service->treHanTheoDois(),
            'sapHetHanThucHiens' => $service->sapHetHanThucHiens(),
            'treHanThucHiens' => $service->treHanThucHiens(),
            'service' => $service
        );
    }

    public function appInfoAction(){
    	
    }
}
