<?php namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use User\Form\QuyenForm;
use User\Entity\Role;

class QuyenController extends AbstractActionController
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

    public function danhSachAction(){
    	$entityManager = $this->getEntityManager();
    	$query = $entityManager->createQuery('select r from User\Entity\Role r');
    	$roles = $query->getResult();

    	return array(
    		'roles' => $roles
    	);
    }

    public function taoQuyenAction(){
        $entityManager = $this->getEntityManager();

        $role = new Role();
        $form = new QuyenForm($entityManager);
        $form->bind($role);

        $request = $this->getRequest();
	    if($request->isPost()){
	        $form->setData($request->getPost());
	        if($form->isValid()){
	          	$entityManager->persist($role);
	          	$entityManager->flush();
	          	//$this->flashMessenger()->addSuccessMessage('Bạn vừa thêm quyền mới thành công!');
	          	return $this->redirect()->toRoute('quyen/crud',array('action'=>'tao-quyen'));
	        }
	    }

        return array(
            'form' => $form
        );
    }

    public function suaQuyenAction(){
    	$roleId = (int) $this->params()->fromRoute('id', 0);
    	if(!$roleId) return $this->redirect()->toRoute('quyen');
    	$entityManager = $this->getEntityManager();
    	$role = $entityManager->getRepository('User\Entity\Role')->find($roleId);
    	$form = new QuyenForm($entityManager);
    	$form->bind($role);

    	$request = $this->getRequest();
	    if($request->isPost()){
	        $form->setData($request->getPost());
	        if($form->isValid()){
	          	$entityManager->flush();
	          	//$this->flashMessenger()->addSuccessMessage('Cập nhật thành công!');
	          	return $this->redirect()->toRoute('quyen');
	        }
	    }

    	return array(
    		'form' => $form,
    		'roleId' => $roleId
    	);
    }
}