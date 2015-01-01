<?php namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManager;

use User\Form\UpdateUserForm;

class IndexController extends AbstractActionController
{
 	protected $entityManager;

    /**
     * @var TaxonomyControllerOptionsInterface
     */
    protected $options;

    public function getEntityManager()
    {
        if(!$this->entityManager)
        {
          $this->entityManager=$this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    public function listAction(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('select u from User\Entity\User u');
        $users = $query->getResult();
        return array(
            'users' => $users
        );
    }
    
    public function editAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user/crud', array(
                'action' => 'add'
            ));
        }
        $entityManager = $this->getEntityManager();        
        $user = $entityManager->getRepository('User\Entity\User')->find($id);
       
        $form = new UpdateUserForm($entityManager);  

        $form->bind($user);        
        die(var_dump($user));
        /*$request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $entityManager->flush();
                return $this->redirect()->toRoute('user/crud', array('action'=>'list'));
            }
        }        
        return array(
            'form' => $form,
            'id'=>$id,
        );*/
    }

    public function viewAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if(!$id){
            return $this->redirect()->toRoute('user');
        }
        
        $entityManager = $this->getEntityManager();
        $dql = 'select u from User\Entity\User u where u.id = :id';
        $query = $entityManager->createQuery($dql);
        $query->setParameter('id', $id);
        $user = $query->getSingleResult();
        return array(
            'user' => $user
        );
    }

    public function updateAction()
    {
        if(!$this->zfcUserAuthentication()->hasIdentity())
         {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
         }

        $id = (int) $this->params()->fromRoute('id', 0);
        if(!$id){
            return $this->redirect()->toRoute('cong_viec');
        }        
        $entityManager = $this->getEntityManager();
        $user = $entityManager->getRepository('User\Entity\User')->find($id);
        $form = new UpdateUserForm($entityManager);
        $form->bind($user);
        //die(var_dump($form));
        if(!$user)
        {
            return $this->redirect()->toRoute('cong_viec');  
        }
        else
        {            
            return array(
                'user' => $user,
                'id'=>$id
            ); 
        }
        
    }
}
?>