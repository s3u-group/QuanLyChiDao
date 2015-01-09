<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CongViec\Entity\PhanCong;
use CongViec\Entity\TheoDoi;
use CongViec\Entity\DinhKemTheoDoi;
use CongViec\Form\TheoDoiForm;
use CongViec\Form\TheoDoiFieldset;

class TheoDoiController extends AbstractActionController
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
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc, User\Entity\User u WHERE cv.id=pc.congVan and pc.vaiTro='.PhanCong::NGUOI_PHAN_CONG.' and cv.nguoiTao=u.id and pc.nguoiThucHien='.$idUser.' '.$dk);
                if($dk=='')
                {
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and pc.vaiTro='.PhanCong::NGUOI_PHAN_CONG.' and pc.nguoiThucHien='.$idUser);
                }
                $congViecs=$query->getResult();
                
                
            }
            else
            {
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and pc.vaiTro='.PhanCong::NGUOI_PHAN_CONG.' and pc.nguoiThucHien='.$idUser);
                $congViecs=$query->getResult();
            }
        }
        else
        {
            $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and pc.vaiTro='.PhanCong::NGUOI_PHAN_CONG.' and pc.nguoiThucHien='.$idUser);
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

     public function baoCaoNghiemThuAction(){
        
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
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc, User\Entity\User u WHERE cv.id=pc.congVan and (pc.vaiTro='.PhanCong::NGUOI_PHAN_CONG.' or pc.vaiTro='.PhanCong::NGUOI_CAP_NHAT.' or pc.vaiTro='.PhanCong::NGUOI_THEO_DOI.' ) and cv.nguoiTao=u.id and pc.nguoiThucHien='.$idUser.' '.$dk);
                if($dk=='')
                {
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and (pc.vaiTro='.PhanCong::NGUOI_PHAN_CONG.' or pc.vaiTro='.PhanCong::NGUOI_CAP_NHAT.' or pc.vaiTro='.PhanCong::NGUOI_THEO_DOI.' ) and pc.nguoiThucHien='.$idUser);
                }
                $congViecs=$query->getResult();
                
                
            }
            else
            {
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and (pc.vaiTro='.PhanCong::NGUOI_PHAN_CONG.' or pc.vaiTro='.PhanCong::NGUOI_CAP_NHAT.' or pc.vaiTro='.PhanCong::NGUOI_THEO_DOI.' ) and pc.nguoiThucHien='.$idUser);
                $congViecs=$query->getResult();
            }
        }
        else
        {
            $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id=pc.congVan and (pc.vaiTro='.PhanCong::NGUOI_PHAN_CONG.' or pc.vaiTro='.PhanCong::NGUOI_CAP_NHAT.' or pc.vaiTro='.PhanCong::NGUOI_THEO_DOI.' ) and pc.nguoiThucHien='.$idUser);
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

    public function baoCaoMoiAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('theo_doi/crud',array('action'=>'index'));
        }  
        $entityManager=$this->getEntityManager();  
        $form = new TheoDoiForm($entityManager);
        $baoCao = new TheoDoi();
        $form->bind($baoCao);

        $nguoiThucHiens=$this->KiemTraQuyenCuaUser()->capNhatCongViec($id);
        if(!$nguoiThucHiens){
            $this->flashMessenger()->addMessage('Xin lỗi bạn không có quyền thêm báo cáo cho công việc này');
            return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$id));
        }
        // lấy người thực hiện
        $nguoiThucHiens=$nguoiThucHiens->getNguoiThucHiens();

        $request=$this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                //die(var_dump('isValid'));
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $post=$post['theo-doi']['dinhKems'];
                $entityManager->persist($baoCao);
                $entityManager->flush();
                $this->dinhKemMoi($post,$baoCao);
                $this->flashMessenger()->addMessage('Thêm báo cáo thành công!');
                return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$id));
                
            }
            else
            {
                //die(var_dump($form->getMessages()));
                $this->flashMessenger()->addMessage('Thêm báo cáo thất bại!');
            }
        }
        return array(
            'form'=>$form,
            'id'=>$id,
            'nguoiThucHiens'=>$nguoiThucHiens,
        );

    }

    public function dinhKemMoi($post,$baoCao)
    {
        $entityManager=$this->getEntityManager();       
        $dinhKems=$post;        
        $dmy=$baoCao->getNgayBaoCao()->format('Y/m/d');
        $path="./public/filedinhkems/".$dmy.'/';
        if (!file_exists($path)) {            
            mkdir($path, 0700, true);
        }
        foreach ($dinhKems as $dinhKem) {
            if($dinhKem['error']==0)
            {
                //die(var_dump($baoCao->getId()));
                $uniqueToken=md5(uniqid(mt_rand(),true));
                $newName=$uniqueToken.'_'.$dinhKem['name'];
                $filter = new \Zend\Filter\File\Rename($path.$newName);
                $filter->filter($dinhKem);
                $dk=new DinhKemTheoDoi();
                
                $dk->setUrl($newName);
                $dk->setTheoDoi($baoCao);
                //die(var_dump($dk));
                $entityManager->persist($dk);
                $entityManager->flush();
            }
        }
    }
    public function huyBaoCaoAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('theo_doi/crud',array('action'=>'index'));
        } 
        $entityManager=$this->getEntityManager();
        $baoCao=$entityManager->getRepository('CongViec\Entity\TheoDoi')->find($id); 
        
        $idCongViec=$baoCao->getCongVan()->getId();

        // kiểm tra công việc
        $congViec=$this->KiemTraQuyenCuaUser()->capNhatCongViec($idCongViec);
        if(!$congViec){
            $this->flashMessenger()->addMessage('Xin lỗi bạn không có quyền cập nhật báo cáo trên công việc này');
            return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$idCongViec));
        }

        if($baoCao)
        {            
            $idCongVan=$baoCao->getCongVan()->getId();
            $baoCao->setTrangThai(TheoDoi::DA_HUY);
            //$entityManager->remove($baoCao);
            $entityManager->flush();
            return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'chi-tiet-cong-viec','id'=>$idCongVan));
        }
        else{
            return $this->redirect()->toRoute('theo_doi/crud',array('action'=>'index'));
        }

    }
   
}