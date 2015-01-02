<?php namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManager;

use User\Form\UpdateUserForm;
use User\Form\ChangePassword;
use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;

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
        $emailCu=$user->getEmail();        
        $form = new UpdateUserForm($entityManager);
        $form->bind($user);
        if(!$user)
        {
            return $this->redirect()->toRoute('cong_viec');  
        }

        $request = $this->getRequest();        
        if ($request->isPost()) {            
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $emailMoi=$user->getEmail();                
                if($emailCu!=$emailMoi)
                {
                    $query=$entityManager->createQuery('SELECT u FROM User\Entity\User u WHERE u.email=\''.$emailMoi.'\'');
                    $email=$query->getResult();
                    if($email)
                    {                        
                        return array(
                            'form' => $form,
                            'id'=>$id,
                            'kiemTraEmail'=>1
                        );
                    }                    
                }
                if($request->getPost()->get('gioiTinh')=='Nam')
                {
                    $user->setGioiTinh(1);
                }
                else
                {
                    $user->setGioiTinh(2);
                }
                $entityManager->flush();                
                $this->flashMessenger()->addMessage('Cập nhật thành công!');
                return $this->redirect()->toRoute('user/crud',array('action'=>'update','id'=>$id));
            }
            else
            {
                //die(var_dump($form->getMessages()));
            }
        } 
        return array(
            'form' => $form,
            'id'=>$id,
            'kiemTraEmail'=>0
        );
    }

    public function changePassWordAction()
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
        //$email=$user->getEmail();
        /*$redirect = $this->url()->fromRoute('user');
            $this->getRequest()->getQuery()->set('redirect', $redirect);    
            return $this->forward()->dispatch('zfcuser', array(
                'action' => 'changepassword'
            ));*/
        /*$form = new ChangePassword($entityManager); */       
        $request = $this->getRequest();        
        if ($request->isPost()) {
            $post=$request->getPost();
                    
            $password = $post['matKhauMoi']//This is extremely bad form, don't do it. You don't want any passwords hanging out in plaintext.
            
            $bcrypt = new Bcrypt();
            $bcrypt->setCost(14); // Needs to match password cost in ZfcUser options
            $user->setPassword ($bcrypt->create($password));
            $entityManager->flush();
            var_dump($user);    
            die(var_dump($post));    

            /*$redirect = $this->url()->fromRoute('user');
            $this->getRequest()->getQuery()->set('redirect', $redirect);    
            return $this->forward()->dispatch('zfcuser', array(
                'action' => 'changepassword'
            ));*/
        }

        return array(
            'id'=>$id,
            /*'email'=>$email,
            'form'=>$form,*/
            );

        /*$user = $entityManager->getRepository('User\Entity\User')->find($id);
        $request = $this->getRequest();
        if ($request->isPost()) {            
            
        } */       
    }
}
?>