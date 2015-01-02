<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CongViec\Entity\CongVan;

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
                elseif ($post['tuNgay']=='') {
                    $dk.='and cv.ngayHoanThanh <=\''.$post['denNgay'].'\'';
                }
                elseif ($post['denNgay']=='') {
                    $dk.='and cv.ngayHoanThanh >=\''.$post['tuNgay'].'\'';
                }                
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc, User\Entity\User u WHERE cv.cha is not null and cv.id=pc.congVan and cv.nguoiTao=u.id and pc.nguoiThucHien='.$idUser.' '.$dk);
                $congViecs=$query->getResult();
                
            }
            else
            {
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc WHERE cv.cha is not null and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
                $congViecs=$query->getResult();
            }


            /*if($post['btnSubmit']=='Tìm')
            {
                if($post['dieuKien']=='ten')
                {
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc WHERE cv.cha is not null and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser.' and cv.ten LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'');
                    $congViecs=$query->getResult();
                }
                if($post['dieuKien']=='nguoiTao')
                {
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc, User\Entity\User u WHERE cv.cha is not null and cv.id=pc.congVan and cv.nguoiTao=u.id and pc.nguoiThucHien='.$idUser.' and u.username LIKE '.'\''.'%'.$post['txtDuLieu'].'%'.'\'');
                    $congViecs=$query->getResult();
                }
                
            }
            elseif ($post['btnSubmit']=='Xem hết') {
               $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc WHERE cv.cha is not null and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
               $congViecs=$query->getResult();
            }
            elseif ($post['btnSubmit']=='Tìm theo ngày') {

                if($post['tuNgay']==''){
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc WHERE cv.cha is not null and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser.' and cv.ngayHoanThanh <=\''.$post['denNgay'].'\'');
                }
                if($post['denNgay']==''){
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc WHERE cv.cha is not null and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser.' and cv.ngayHoanThanh >=\''.$post['tuNgay'].'\'');
                }
                if($post['tuNgay']!=''&&$post['denNgay']!='')
                {
                    $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc WHERE cv.cha is not null and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser.' and cv.ngayHoanThanh >=\''.$post['tuNgay'].'\''.' and cv.ngayHoanThanh <=\''.$post['denNgay'].'\'');
                }
                $congViecs=$query->getResult();
            }*/
            
        }
        else
        {
            $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongVan cv, CongViec\Entity\PhanCong pc WHERE cv.cha is not null and cv.id=pc.congVan and pc.nguoiThucHien='.$idUser);
            $congViecs=$query->getResult();
        }
        if($dieuKienLoc=='')
        {
            $dieuKienLoc='radioLocTatCa';
        }
        return array(
            'congViecs'=>$congViecs,
            'dieuKienLoc'=>$dieuKienLoc,
            'tuNgay'=>$tuNgay,
            'denNgay'=>$denNgay,
            'duLieu'=>$duLieu,
            'dieuKien'=>$dieuKien,
        );
        
    }
}