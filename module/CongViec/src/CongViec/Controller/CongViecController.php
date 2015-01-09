<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use CongViec\Entity\CongVan;
use CongViec\Entity\CongViec;
use CongViec\Entity\PhanCong;
use CongViec\Entity\DinhKem;
use CongViec\Form\GiaoViecForm;
use DateTime;
use CongViec\Form\CapNhatCongViecForm;


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
        
        $dieuKienLoc='';
        $dieuKien='';
        $tuNgay='';
        $denNgay='';
        $duLieu='';


        $entityManager=$this->getEntityManager();           
        $request=$this->getRequest();
        
        if($request->isPost())
        {           
            $post=$request->getPost();
            //die(var_dump($post));
            if($post['btnSubmit']!='Xem hết')
            {
                $dk='';
                $dieuKienLoc=$post['dieuKienLoc'];
                $dieuKien=$post['dieuKien'];
                $tuNgay=$post['tuNgay'];
                $denNgay=$post['denNgay'];
                $duLieu=$post['txtDuLieu'];
                if($post['txtDuLieu'])
                {
                    if($post['dieuKien']=='ten'){
                        $dk.=' and cv.ten LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'';
                    }
                    elseif($post['dieuKien']=='nguoiTao'){
                        $dk.='and u.username LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'';
                    }
                }
                if($post['tuNgay']!=''&&$post['denNgay']!='')
                {
                    $dk.='and cv.ngayHoanThanh >=\''.$post['tuNgay'].'\''.' and cv.ngayHoanThanh <=\''.$post['denNgay'].'\'';
                }
                elseif ($post['tuNgay']==''&&$post['denNgay']!='') {
                    $dk.='and cv.ngayHoanThanh <=\''.$post['denNgay'].'\'';
                }
                elseif ($post['denNgay']==''&&$post['tuNgay']!='') {
                    $dk.='and cv.ngayHoanThanh >=\''.$post['tuNgay'].'\'';
                }             
                if($dk!='')
                {
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc, User\Entity\User u WHERE cv.trangThai!='.CongViec::TRE_HAN.' and cv.trangThai!='.CongViec::HOAN_THANH.' and cv.id=pc.congVan and cv.nguoiTao=u.id and pc.nguoiThucHien='.$idUser.' '.$dk);
                }
                elseif($dk=='')
                {
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.trangThai!='.CongViec::TRE_HAN.' and cv.trangThai!='.CongViec::HOAN_THANH.' and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
                }

                $congViecs=$query->getResult();
                
                
            }
            else
            {
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.trangThai!='.CongViec::TRE_HAN.' and cv.trangThai!='.CongViec::HOAN_THANH.' and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
                $congViecs=$query->getResult();
            }
        }
        else
        {
            $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.trangThai!='.CongViec::TRE_HAN.' and cv.trangThai!='.CongViec::HOAN_THANH.' and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
            $congViecs=$query->getResult();
        }
        if($dieuKienLoc=='')
        {
            $dieuKienLoc='Tất cả';
        }

        /*die(var_dump($congViecs));*/
        return array(
            'congViecs'=>$congViecs,
            'dieuKienLoc'=>$dieuKienLoc,
            'tuNgay'=>$tuNgay,
            'denNgay'=>$denNgay,
            'duLieu'=>$duLieu,
            'dieuKien'=>$dieuKien,
        );
        
    }
    public function nhatKyCongViecAction(){
        if(!$this->zfcUserAuthentication()->hasIdentity())
        {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        else
        {
            $idUser=$this->zfcUserAuthentication()->getIdentity()->getId();
        }
        
        $dieuKienLoc='';
        $dieuKien='';
        $duLieu='';


        $entityManager=$this->getEntityManager();           
        $request=$this->getRequest();
        
        if($request->isPost())
        {           
            $post=$request->getPost();
            //die(var_dump($post));
            if($post['btnSubmit']!='Xem hết')
            {
                $dk='';
                $dieuKienLoc=$post['dieuKienLoc'];
                $dieuKien=$post['dieuKien'];
                $duLieu=$post['txtDuLieu'];
                if($post['txtDuLieu'])
                {
                    if($post['dieuKien']=='ten'){
                        $dk.=' and cv.ten LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'';
                    }
                    elseif($post['dieuKien']=='nguoiTao'){
                        $dk.='and u.username LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'';
                    }
                }
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc, User\Entity\User u WHERE cv.id=pc.congVan and cv.nguoiTao=u.id and pc.nguoiThucHien='.$idUser.' '.$dk);
                if($dk=='')
                {
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
                }
                $congViecs=$query->getResult();
                
                
            }
            else
            {
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
                $congViecs=$query->getResult();
            }
        }
        else
        {
            $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
            $congViecs=$query->getResult();
        }
        if($dieuKienLoc=='')
        {
            $dieuKienLoc='Trễ hạn';
        }
        //die(var_dump($congViecs));
        return array(
            'congViecs'=>$congViecs,
            'dieuKienLoc'=>$dieuKienLoc,
            'duLieu'=>$duLieu,
            'dieuKien'=>$dieuKien,
        );
        
    }

    public function giaoViecAction(){
        $entityManager = $this->getEntityManager();
        $form = new GiaoViecForm($entityManager, $this->getServiceLocator());
        $congViec = new CongViec();

        $request = $this->getRequest();
        if($request->isPost()){
            $form->bind($congViec);
            $form->setData($request->getPost());
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            if($form->isValid()){

                $pcNguoiGiaoViec = new PhanCong();
                $pcNguoiGiaoViec->setVaiTro(\CongViec\Entity\PhanCong::NGUOI_PHAN_CONG);
                $pcNguoiGiaoViec->setNguoiThucHien($congViec->getCha()->getNguoiKy());
                $congViec->addNguoiThucHiens(array($pcNguoiGiaoViec));

                $entityManager->persist($congViec);
                $entityManager->flush();

                $post = $post['congViec']['dinhKems'];
                $this->dinhKemMoi($entityManager, $post, $congViec);

                $form->bind($congViec);
            }
        }

        return array(
            'form' => $form,
        );
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

    public function ajaxGetNhanVienAction(){
        $entityManager = $this->getEntityManager();
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()){
            $data = $request->getPost();
            $id = $data['id'];
            $query = $entityManager->createQuery('select u from User\Entity\User u where u.id = :id');
            $query->setParamater('id', $id);
            $nhanVien = $query->getResult();
            $response = array(
                'id' => $id,
                'ten' => $nhanVien->getHoTen()
            );
            $json = new JsonModel($response);
            return $json;
        }
    }

    public function chiTietCongViecAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'index'));
        }  
        $entityManager=$this->getEntityManager();
        $congViec=$entityManager->getRepository('CongViec\Entity\CongViec')->find($id);
        $form = new CapNhatCongViecForm($entityManager);
        $form->bind($congViec);
        $request=$this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid()){
                $entityManager->flush();
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                //die(var_dump($post));
                $post=$post['congViecs']['dinhKems'];
                $this->dinhKemMoi($entityManager,$post,$congViec);
                $this->flashMessenger()->addMessage('Cập nhật công việc thành công!');
                return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$id));
            }
            else
            {
                $this->flashMessenger()->addMessage('Cập nhật công việc thất bại!');
                //die(var_dump($form->getMessages()));
            }
            
        }
        $query=$entityManager->createQuery('SELECT td FROM CongViec\Entity\TheoDoi td JOIN td.congVan cv WHERE cv.id=\''.$id.'\'');
        $theoDois=$query->getResult();
        return array(
            'congViec'=>$congViec,
            'theoDois'=>$theoDois,
            'form'=>$form,
        );
    }

    public function xoaDinhKemAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'index'));
        }
        $entityManager=$this->getEntityManager();
        $dinhKem=$entityManager->getRepository('CongViec\Entity\DinhKem')->find($id);
        $idCongViec=$dinhKem->getCongVan()->getId();

        if(!$this->KiemTraQuyenCuaUser()->huyDinhKem($idCongViec)){
            $this->flashMessenger()->addMessage('Xin lỗi bạn không có quyền hủy đính kèm của công việc này');
            return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$idCongViec));
     
        }

        $ymd=$dinhKem->getCongVan()->getNgayTao()->format('Y/m/d');
        $path="./public/filedinhkems/".$ymd.'/';
        $mask =__ROOT_PATH__.'/public/filedinhkems/'.$ymd.'/'.$dinhKem->getUrl();
        array_map( "unlink", glob( $mask )); 
        $entityManager->remove($dinhKem); 
        $entityManager->flush();
        return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$idCongViec));
    }

    

    public function hoanThanhAction()
    {        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'index'));
        }

        $entityManager=$this->getEntityManager();
        $congViec=$this->KiemTraQuyenCuaUser()->capNhatCongViec($id);
        if(!$congViec){
            $this->flashMessenger()->addMessage('Xin lỗi bạn không có quyền cập nhật công việc này');
            return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$id));
        }
        
        $ngayHienTai=date('Y-m-d');        
        $ngayHoanThanh=$congViec->getNgayHoanThanh()->format('Y-m-d');

        
        if(strtotime($ngayHienTai) > strtotime($ngayHoanThanh)){
            $congViec->setTrangThai(CongViec::TRE_HAN);
        }
        else
        {
            $congViec->setTrangThai(CongViec::HOAN_THANH);
        }
        $ngayHienTai = DateTime::createFromFormat('Y-m-d', $ngayHienTai);
        $congViec->setNgayHoanThanhThuc($ngayHienTai);
        $entityManager->flush(); 
        return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$id));
    }

    public function dinhKemMoi($entityManager,$post,$congViec)
    {
        //$entityManager=$this->getEntityManager();       
        $dinhKems=$post;        
        $ymd=$congViec->getNgayTao()->format('Y/m/d');
        $path="./public/filedinhkems/".$ymd.'/';
        if (!file_exists($path)) {            
            mkdir($path, 0700, true);
        }
        foreach ($dinhKems as $dinhKem) {
            if($dinhKem['error']==0)
            {
                $uniqueToken=md5(uniqid(mt_rand(),true));
                $newName=$uniqueToken.'_'.$dinhKem['name'];
                $filter = new \Zend\Filter\File\Rename($path.$newName);
                $filter->filter($dinhKem);
                $dk=new DinhKem();
                
                $dk->setUrl($newName);
                $dk->setCongVan($congViec);
                $entityManager->persist($dk);
                $entityManager->flush();
            }
        }
    }
}