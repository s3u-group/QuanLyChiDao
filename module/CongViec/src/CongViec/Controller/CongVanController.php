<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CongViec\Entity\CongVan;
use CongViec\Form\TaoCongVanForm;

class CongVanController extends AbstractActionController
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
        $entityManager=$this->getEntityManager();
        
    }
    public function congVanMoiAction()
    {
        if(!$this->zfcUserAuthentication()->hasIdentity())
        {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        $entityManager=$this->getEntityManager();
        $form= new TaoCongVanForm($entityManager);
        $congVan= new CongVan();
        $form->bind($congVan); 

        return array(
            'form'=>$form,
        );       
    }
}