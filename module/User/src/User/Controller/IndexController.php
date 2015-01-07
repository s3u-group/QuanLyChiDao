<?php namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManager;

use User\Form\UpdateUserForm;
use User\Entity\User;
use User\Entity\UserRole;
use Zend\Crypt\Password\Bcrypt;
use User\Form\CreateAccountForm;

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
                    
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post=$request->getPost();            
            $dk='u.displayName LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'';
            $query=$entityManager->createQuery('SELECT u FROM User\Entity\User u WHERE '.$dk);
            $users=$query->getResult();
            
            return array(                
                'users' => $users
            );
        }
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
        $request = $this->getRequest();
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
        );
    }

    public function viewAction(){
        $id =$this->zfcUserAuthentication()->getIdentity()->getId();
        $entityManager = $this->getEntityManager();

        $dql = 'select u from User\Entity\User u where u.id = :id';
        $query = $entityManager->createQuery($dql);
        $query->setParameter('id', $id);
        $user = $query->getSingleResult();

        $form = new UpdateUserForm($entityManager);
        $form->bind($user);
        $request = $this->getRequest();
        if ($request->isPost()) {
            //Code nếu có yêu cầu
        }
        return array(
            'form' => $form,            
            'id'=>$id,            
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
                die(var_dump($user));
                $entityManager->flush();                
                $this->flashMessenger()->addMessage('Cập nhật thành công!');
                return $this->redirect()->toRoute('user/crud',array('action'=>'update','id'=>$id));
            }
            else
            {                
            }
        } 
        return array(
            'form' => $form,
            'id'=>$id,
            'kiemTraEmail'=>0,            
        );
    }    

    public function adminChangePassWordAction()
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
        $request = $this->getRequest();        
        if ($request->isPost()) {
            $post=$request->getPost();

            $bcrypt = new Bcrypt();
            $bcrypt->setCost(14);

            /*$passAdmin=$bcrypt->create($post['matKhauAdmin']);
            $admin = $entityManager->getRepository('User\Entity\User')->find(1);
            if($admin->getPassword()==$passAdmin)
            {*/
                $password = $post['matKhauMoi'];            
                $user->setPassword ($bcrypt->create($password));
                $entityManager->flush();  
                return $this->redirect()->toRoute('user/crud',array('action'=>'list'));
            /*}
            else
            {                
            }*/
        }

        return array(
            'id'=>$id,            
            );        
    }

    public function danhMucDonViAction()
    {
        $entityManager = $this->getEntityManager();
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post=$request->getPost();
            $dk='dv.tenDonVi LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'';
            $query=$entityManager->createQuery('SELECT dv FROM User\Entity\DonVi dv WHERE '.$dk);
            $donVis = $query->getResult();        
            if($donVis)
            {                
                $listCount=array();
                foreach ($donVis as $donVi) {               
                    $dql = 'select count(u.id) as Tong from User\Entity\User u where u.donVi = :id';
                    $query = $entityManager->createQuery($dql);
                    $query->setParameter('id', $donVi->getId());
                    $count = $query->getSingleResult();
                    $listCount[]=$count['Tong'];                
                }
                return array(
                    'donVis'=>$donVis,
                    'listCount'=>$listCount,
                    'kiemTraDonVi'=>1
                );            
            }
            else
            {
                return array(
                    'kiemTraDonVi'=>0
                );
            }
        }

        $dql = 'select dv from User\Entity\DonVi dv';
        $query = $entityManager->createQuery($dql);        
        $donVis = $query->getResult();        
        if($donVis)
        {
            $listCount=array();
            foreach ($donVis as $donVi) {               
                $dql = 'select count(u.id) as Tong from User\Entity\User u where u.donVi = :id';
                $query = $entityManager->createQuery($dql);
                $query->setParameter('id', $donVi->getId());
                $count = $query->getSingleResult();
                $listCount[]=$count['Tong'];                
            }
            return array(
                'donVis'=>$donVis,
                'listCount'=>$listCount,
                'kiemTraDonVi'=>1
            );            
        }
        else
        {
            return array(
                'kiemTraDonVi'=>0
            );
        }        
    }

    public function chiTietDonViAction()
    {
        $idDonVi = (int) $this->params()->fromRoute('id', 0);
        if(!$idDonVi){
            return $this->redirect()->toRoute('cong_viec');
        }
        $entityManager = $this->getEntityManager();

        $dql = 'select u from User\Entity\User u where u.donVi = :idDonVi';
        $query = $entityManager->createQuery($dql);
        $query->setParameter('idDonVi', $idDonVi);
        $users = $query->getResult();
        if($users)
        {
            return array(
                'users'=>$users,                
            );            
        }
        else
        {
            return $this->redirect()->toRoute('user/crud',array('action'=>'danhMucDonVi'));
        }
    }

    public function createAccountAction()
    {
        if(!$this->zfcUserAuthentication()->hasIdentity())
        {
            return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        $id =$this->zfcUserAuthentication()->getIdentity()->getId();
        if($id!=1)
        {            
            return $this->redirect()->toRoute('user/crud',array('action'=>'list'));
        }
        $entityManager = $this->getEntityManager();
        $user=new User();
        $form = new CreateAccountForm($entityManager);
        $form->bind($user);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) 
            {                
                $username=$request->getPost()->get('user')['username'];
                $email=$request->getPost()->get('user')['email'];
                $gioiTinh=$request->getPost()->get('gioiTinh');

                $dql = 'select u from User\Entity\User u where u.username = :username';
                $query = $entityManager->createQuery($dql);
                $query->setParameter('username', $username);
                $tenDangNhap = $query->getResult();

                $dql = 'select u from User\Entity\User u where u.email = :email';
                $query = $entityManager->createQuery($dql);
                $query->setParameter('email', $email);
                $kiemTraEmail = $query->getResult();                

                if((!$tenDangNhap)&&(!$kiemTraEmail))                
                {                    
                    if($gioiTinh=='Nam')
                    {
                        $user->setGioiTinh(1);
                    }
                    else
                    {
                        $user->setGioiTinh(2);
                    }
                    $bcrypt = new Bcrypt();
                    $bcrypt->setCost(14);                    
                    $pass=$request->getPost()->get('user')['password'];
                    $user->setPassword ($bcrypt->create($pass));

                    $entityManager->persist($user);
                    $entityManager->flush();

                    $dql = 'select u from User\Entity\User u where u.username = :username';
                    $query = $entityManager->createQuery($dql);
                    $query->setParameter('username', $username);
                    $userId = $query->getResult();
                    if($userId)
                    {
                        $userRole=new UserRole();                        
                        $userRole->setUserId($userId[0]->getId());
                        $userRole->setRoleId(2);//Tạo tài khoản với role 'người dùng'

                        $entityManager->persist($userRole);
                        $entityManager->flush();
                    }

                    $this->flashMessenger()->addMessage('Tạo tài khoản thành công!');
                    return $this->redirect()->toRoute('user/crud',array('action'=>'list'));
                }
                else
                {
                    if($tenDangNhap&&$kiemTraEmail)
                    {
                        return array(
                            'form' => $form,
                            'kiemTraEmail'=>1,
                            'kiemTraUsername'=>1
                        );
                    }

                    if($tenDangNhap)
                    {
                        return array(
                            'form' => $form,
                            'kiemTraEmail'=>0,
                            'kiemTraUsername'=>1
                        );
                    }

                    if($kiemTraEmail)
                    {
                        return array(
                            'form' => $form,
                            'kiemTraEmail'=>1,
                            'kiemTraUsername'=>0
                        );
                    }

                }
            }
            else//not valid
            {
                //var_dump($form->getMessages());                
            }
        }
        
        return array(
            'form' => $form,
            'kiemTraEmail'=>0,
            'kiemTraUsername'=>0
        );
    }    
}
?>