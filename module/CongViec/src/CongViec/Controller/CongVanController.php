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
        $entityManager=$this->getEntityManager();
        
    }
    public function congVanMoiAction()
    {
        $entityManager=$this->getEntityManager();
        $form= new TaoCongVanForm($entityManager);
        $congVan= new CongVan();
        $form->bind($congVan); 

        return array(
            'form'=>$form,
        );       
    }
}