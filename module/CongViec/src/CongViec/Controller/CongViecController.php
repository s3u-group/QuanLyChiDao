<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use CongViec\Entity\CongVan;
use CongViec\Form\GiaoViecForm;

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


        $entityManager=$this->getEntityManager();           
        $request=$this->getRequest();
        if($request->isPost())
        {           
            $post=$request->getPost();
            if($post['btnSubmit']!='Xem hết')
            {
                $dk='';
                $dieuKienLoc=$post['dieuKienLoc'];
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
                /*var_dump($congViecs);
                die(var_dump($dk));*/
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
        return array(
            'congViecs'=>$congViecs,
            'dieuKienLoc'=>$dieuKienLoc,
        );
        
    }

    public function giaoViecAction(){
        $entityManager = $this->getEntityManager();
        $form = new GiaoViecForm($entityManager);
        $congVan = new CongVan();
        $form->bind($congVan);

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $entityManager->persist($congVan);
                $entityManager->flush();
            }
        }

        return array(
            'form' => $form
        );
    }
}