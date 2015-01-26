<?php namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use User\Entity\DonVi;
use User\Form\CreateDonViForm;
use User\Form\UpdateDonViForm;

class DonViController extends AbstractActionController
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

    public function danhMucAction(){
      $entityManager = $this->getEntityManager();

      /*$query = $entityManager->createQuery('select dv from User\Entity\DonVi dv');
      $donVis = $query->getResult();*/

      $qb = $entityManager->createQueryBuilder();
      $qb->select('dv')->from('User\Entity\DonVi', 'dv');

      $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        
      $paginator = new Paginator($adapter);
      $paginator->setDefaultItemCountPerPage(10);     
      $page = (int)$this->params('page');
      if($page) 
          $paginator->setCurrentPageNumber($page);

      return array(
        'donVis' => $paginator
      );
    }

    public function taoMoiAction(){
      $entityManager = $this->getEntityManager();
      $donVi = new DonVi();
      $form = new CreateDonViForm($entityManager);
      $form->bind($donVi);

      $request = $this->getRequest();
      if($request->isPost()){
        $form->setData($request->getPost());
        if($form->isValid()){
          $entityManager->persist($donVi);
          $entityManager->flush();
          $this->flashMessenger()->addSuccessMessage('Bạn vừa thêm đơn vị mới thành công!');
          return $this->redirect()->toRoute('don_vi/crud',array('action'=>'tao-moi'));
        }
      }

      return array(
        'form' => $form
      );
    }

    public function capNhatAction(){
      $donViId = (int) $this->params()->fromRoute('id', 0);
      if(!$donViId){
          return $this->redirect()->toRoute('don_vi');
      }
      $entityManager = $this->getEntityManager();
      $donVi = $entityManager->getRepository('User\Entity\DonVi')->find($donViId);
      $form = new UpdateDonViForm($entityManager, $donViId);
      $form->bind($donVi);

      $request = $this->getRequest();
      if($request->isPost()){
        $form->setData($request->getPost());
        if($form->isValid()){
          $entityManager->flush();
          $this->flashMessenger()->addSuccessMessage('Bạn cập nhật thành công thông tin cho một đơn vị!');
          return $this->redirect()->toRoute('don_vi');
        }
      }

      return array(
        'form' => $form,
        'donViId' => $donViId
      );
    }
}