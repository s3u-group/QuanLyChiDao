<?php namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManager;

use User\Form\UpdateUserForm;
use User\Entity\User;
use User\Entity\DonVi;
use User\Entity\UserRole;
use Zend\Crypt\Password\Bcrypt;
use User\Form\CreateAccountForm;
use User\Form\CreateDonViForm;
use User\Form\UpdateDonViForm;
use Zend\View\Model\JsonModel;

use User\Form\ThemNhanVienForm;
use User\Form\LocDanhSachNhanVienForm;

class IndexController extends AbstractActionController
{
 	protected $entityManager;

    protected $userService;

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

    public function getUserService()
    {
        if (!$this->userService instanceof UserService) {
            $this->userService = $this->getServiceLocator()->get('user_service');
        }
        return $this->userService;
    }

    public function themNhanVienAction(){
        $entityManager = $this->getEntityManager();

        $form = new ThemNhanVienForm($entityManager);
        $user = new User();
        $form->bind($user);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            $defaultRole = $entityManager->getRepository('User\Entity\Role')
                                  ->findOneBy(array('roleId' => 'cong-viec-can-xu-ly'));
            $user->addRole($defaultRole);
            if($form->isValid()){
                $entityManager->persist($user);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Bạn vừa thêm nhân viên mới thành công!');
                return $this->redirect()->toRoute('user/crud',array('action'=>'them-nhan-vien'));
            }
        }

        return array(
            'form' => $form
        );
    }

    public function danhSachNhanVienAction(){
        $entityManager = $this->getEntityManager();
        $form = new LocDanhSachNhanVienForm();

        $request=$this->getRequest();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('nv')
            ->from('User\Entity\User', 'nv')
            ;

        if($request->isPost()){
            $post = $request->getPost();
            $form->setData($post);

            /**
             * Tim nhanh
             */
            if(isset($post['tuKhoa']) && $post['tuKhoa'] != '' ){
                if($post['tieuChi'] == 1){
                    // tim theo ho ten
                    $qb->andWhere('CONCAT(nv.ho, \' \', nv.ten) like ?1');
                    $qb->setParameter(1, '%'.$post['tuKhoa'].'%');
                }
                else{
                    // tim theo dien thoai
                    $qb->andWhere('nv.dienThoai like ?2');
                    $qb->setParameter(2, '%'.$post['tuKhoa'].'%');
                }
            }
        }

        $query = $qb->getQuery();
        $nhanViens = $query->getResult();

        return array(
            'form' => $form,
            'nhanViens'=>$nhanViens,
        );

    }

    public function capNhatNhanVienAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if(!$id){
            return $this->redirect()->toRoute('user/crud', array('action'=>'danh-sach-nhan-vien'));
        }

        $entityManager = $this->getEntityManager();

        $user = $entityManager->getRepository('User\Entity\User')->find($id);
        $form = new UpdateUserForm($entityManager);
        $form->bind($user);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Bạn vừa thay đổi thành công thông tin của một nhân viên!');
                return $this->redirect()->toRoute('user/crud',array('action'=>'danh-sach-nhan-vien'));
            }
        }

        return array(
            'form'=> $form,
            'idUser' => $id
        );
    }

    public function capTaiKhoanAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if(!$id){
            return $this->redirect()->toRoute('user/crud', array('action'=>'danh-sach-nhan-vien'));
        }
        $entityManager = $this->getEntityManager();

        $user = $entityManager->getRepository('User\Entity\User')->find($id);
        $form = new CreateAccountForm($entityManager);
        $form->bind($user);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $user = $this->getUserService()->edit($form, (array)$request->getPost(), $user);
                
                $this->flashMessenger()->addSuccessMessage('Bạn vừa thay đổi thành công thông tin tài khoản của một nhân viên!');
                return $this->redirect()->toRoute('user/crud',array('action'=>'danh-sach-nhan-vien'));
            }
        }

        return array(
            'form' => $form,
            'idUser' => $id
        );
    }

    public function listAction(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('select u from User\Entity\User u ORDER BY u.displayName ASC');
        $users = $query->getResult();

        $request = $this->getRequest();
        $txtDuLieu='';
        if ($request->isPost()) 
        {
            $post=$request->getPost();       
            $txtDuLieu=$post['txtDuLieu']; 
            $dk='u.displayName LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'';
            $query=$entityManager->createQuery('SELECT u FROM User\Entity\User u WHERE '.$dk.'ORDER BY u.displayName ASC');
            $users=$query->getResult();
        }        
        return array(            
            'users' => $users,
            'txtDuLieu'=>$txtDuLieu
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
        $donVis = $entityManager->getRepository('User\Entity\DonVi')->findAll();
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
                            'donVis'=>$donVis,
                            'id'=>$id,
                            'kiemTraEmail'=>1
                        );
                    }                    
                }                
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
            'donVis'=>$donVis,
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
            $password = $post['matKhauMoi'];
            $user->setPassword ($bcrypt->create($password));
            $entityManager->flush();
            return $this->redirect()->toRoute('user/crud',array('action'=>'list'));
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
            $query=$entityManager->createQuery('SELECT dv FROM User\Entity\DonVi dv WHERE '.$dk.'ORDER BY dv.tenDonVi ASC');
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

        $dql = 'select dv from User\Entity\DonVi dv ORDER BY dv.tenDonVi ASC';
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
        $entityManager = $this->getEntityManager();
        $donVis = $entityManager->getRepository('User\Entity\DonVi')->findAll();
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
                $dienThoai=$request->getPost()->get('user')['dienThoai'];                

                $dql = 'select u from User\Entity\User u where u.username = :username';
                $query = $entityManager->createQuery($dql);
                $query->setParameter('username', $username);
                $tenDangNhap = $query->getResult();

                $dql = 'select u from User\Entity\User u where u.email = :email';
                $query = $entityManager->createQuery($dql);
                $query->setParameter('email', $email);
                $kiemTraEmail = $query->getResult();

                $dql = 'select u from User\Entity\User u where u.dienThoai = :dienThoai';
                $query = $entityManager->createQuery($dql);
                $query->setParameter('dienThoai', $dienThoai);
                $kiemTraSoDienThoai = $query->getResult();                

                if((!$tenDangNhap)&&(!$kiemTraEmail)&&(!$kiemTraSoDienThoai))                
                {                    
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
                        $userRole->setRoleId(2);

                        $entityManager->persist($userRole);
                        $entityManager->flush();
                    }

                    $this->flashMessenger()->addMessage('Tạo tài khoản thành công!');
                    return $this->redirect()->toRoute('user/crud',array('action'=>'list'));
                }
                else
                {
                    if($tenDangNhap)
                    {
                        return array(
                            'form' => $form,
                            'donVis'=>$donVis,
                            'kiemTraEmail'=>0,
                            'kiemTraUsername'=>1,
                            'kiemTraSoDienThoai'=>0
                        );
                    }
                    if($kiemTraSoDienThoai)
                    {
                        return array(
                            'form' => $form,
                            'donVis'=>$donVis,
                            'kiemTraEmail'=>0,
                            'kiemTraUsername'=>0,
                            'kiemTraSoDienThoai'=>1
                        );
                    }

                    if($kiemTraEmail)
                    {
                        return array(
                            'form' => $form,
                            'donVis'=>$donVis,
                            'kiemTraEmail'=>1,
                            'kiemTraUsername'=>0,
                            'kiemTraSoDienThoai'=>0
                        );
                    }                    
                }
            }
            else
            {
                //var_dump($form->getMessages());
            }
        }
        
        return array(
            'form' => $form,
            'donVis'=>$donVis,
            'kiemTraEmail'=>0,
            'kiemTraUsername'=>0,
            'kiemTraSoDienThoai'=>0
        );
    }

    public function taoDonViAction()  
    {
        if(!$this->zfcUserAuthentication()->hasIdentity())
        {
            return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        $id =$this->zfcUserAuthentication()->getIdentity()->getId();        

        $entityManager = $this->getEntityManager();
        $donVi=new DonVi();
        $form = new CreateDonViForm($entityManager);
        $form->bind($donVi);

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $tenDonVi=$request->getPost()->get('don-vi')['tenDonVi'];
            $form->setData($request->getPost());
            if ($form->isValid()) 
            {
                $dql = 'select dv from User\Entity\DonVi dv where dv.tenDonVi = :tenDonVi';
                $query = $entityManager->createQuery($dql);
                $query->setParameter('tenDonVi', $tenDonVi);
                $donVis = $query->getResult();                
                if(!$donVis)
                {
                    $entityManager->persist($donVi);
                    $entityManager->flush();
                    $this->flashMessenger()->addMessage('Tạo đơn vị thành công!');
                    return $this->redirect()->toRoute('user/crud',array('action'=>'danh-muc-don-vi'));
                }
                else
                {
                    return array(
                        'form' => $form,
                        'kiemTraDonVi'=>1
                    );
                }
            }            
        }
        return array(
            'form' => $form,
            'kiemTraDonVi'=>0
        );
    }

    public function suaDonViAction()
    {
        if(!$this->zfcUserAuthentication()->hasIdentity())
        {
            return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if(!$id){            
            return $this->redirect()->toRoute('user/crud',array('action'=>'danh-muc-don-vi'));
        }        
        $entityManager = $this->getEntityManager();        

        $donVi = $entityManager->getRepository('User\Entity\DonVi')->find($id);
        $tenCu=$donVi->getTenDonVi();        
        $form = new UpdateDonViForm($entityManager);
        $form->bind($donVi);

        $request = $this->getRequest();        
        if ($request->isPost()) {            
            $form->setData($request->getPost());            
            if ($form->isValid()) {
                $tenMoi=$request->getPost()->get('don-vi')['tenDonVi'];
                if($tenCu!=$tenMoi)
                {
                    $query=$entityManager->createQuery('SELECT dv FROM User\Entity\DonVi dv WHERE dv.tenDonVi=\''.$tenMoi.'\'');
                    $donVis=$query->getResult();
                    if($donVis)
                    {                        
                        return array(
                            'form' => $form,
                            'id'=>$id,
                            'kiemTraTenDonVi'=>1
                        );
                    }                    
                }                
                $entityManager->flush();                
                $this->flashMessenger()->addMessage('Cập nhật thành công!');
                return $this->redirect()->toRoute('user/crud',array('action'=>'sua-don-vi','id'=>$id));
            }
            else
            {}
        } 

        return array(
            'form' => $form,
            'id'=>$id,
            'kiemTraTenDonVi'=>0,
        );
    }

    public function phanQuyenAction(){
        $entityManager=$this->getEntityManager();
        $request=$this->getRequest();
        if($request->isPost()){
            $post=$request->getPost();
            $idUser=$post['idUser'];
            $quyens=$post['quyens'];
            $user=$entityManager->getRepository('User\Entity\User')->find($idUser);
            $user->removeRole($user->getRoles());
            $entityManager->flush();
            if($quyens){
                foreach ($quyens as $quyen) {
                    $userRole=new UserRole();
                    $userRole->setUserId($idUser);
                    $userRole->setRoleId($quyen);
                    $entityManager->persist($userRole);
                    $entityManager->flush();
                }    
            }                
        }
        $donVis=$entityManager->getRepository('User\Entity\DonVi')->findAll();
        $quyens=$entityManager->getRepository('User\Entity\Role')->findAll();


        return array(
            'donVis'=>$donVis,
            'quyens'=>$quyens,
        );
    }


    public function userRolesAction(){
        $entityManager=$this->getEntityManager();
        $response=array();
        $request=$this->getRequest();
        if($request->isXmlHttpRequest())
        {
          $data=$request->getPost();
          $id=$data['id'];
          if($id){
            $user=$entityManager->getRepository('User\Entity\User')->find($id);
            foreach ($user->getRoles() as $role) {
                if($role->getRoleId()!='khach'){
                    $response[]=array(
                        'id'=>$role->getId(),
                    );
                }                    
            }
          }
        }
        $json = new JsonModel($response);
        return $json;
    }

    public function ajaxGetToChucAction(){
        $entityManager = $this->getEntityManager();
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()){
            $response = array();
            $query = $entityManager->createQuery('select d from User\Entity\DonVi d left join d.nhanViens n');
            $donVis = $query->getResult();
            foreach($donVis as $donVi){
                $nhanViens = $donVi->getNhanViens();
                $childrens = array();
                foreach($nhanViens as $nhanVien){
                    $childrens[] = array(
                        'text' => $nhanVien->getHoTen(),
                        'id' => $nhanVien->getId()
                    );
                }
                $response[] = array(
                    'text' => $donVi->getTenDonVi(),
                    'children' => $childrens
                );
            }

            $json = new JsonModel($response);
            return $json;
        }
    }
}
?>