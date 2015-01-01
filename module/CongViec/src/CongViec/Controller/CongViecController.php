<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CongViec\Entity\CongVan;

class CongViecController extends AbstractActionController
{
	protected $entityManager;

    public function getEntityManager()
    {
        if(!$this->entityManager)
        {
          $this->entityManager=$this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    public function indexAction(){
        if(!$this->zfcUserAuthentication()->hasIdentity())
        {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        else
        {
            $idUser=$this->zfcUserAuthentication()->getIdentity()->getId();
        }
        $entityManager=$this->getEntityManager();
        $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc WHERE cv.cha is not null and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser.' and cv.trangThai!='.CongVan::HOAN_THANH);
        $congViecs=$query->getResult();/*die(var_dump($congViecs));*/
        return array(
            'congViecs'=>$congViecs,
        );
        
    }
}