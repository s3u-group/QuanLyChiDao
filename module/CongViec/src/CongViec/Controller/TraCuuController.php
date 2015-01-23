<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DateTime;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use CongViec\Form\LocNhatKyForm;
use CongViec\Form\LocCongViecDonViForm;

class TraCuuController extends AbstractActionController
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
        if(!$this->zfcUserAuthentication()->hasIdentity()) {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }

        $entityManager=$this->getEntityManager();  

        $form = new LocNhatKyForm($entityManager);

        $request=$this->getRequest();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('cv')
            ->from('CongViec\Entity\CongViec', 'cv')
            ->join('cv.nguoiThucHiens', 'pc')
            ->leftJoin('cv.cha', 'c')
            ->leftJoin('c.nguoiKy', 'nk')
            ->orderBy('cv.ngayTao', 'DESC')
            ;

        if($request->isPost()){
            $post = $request->getPost();

            /**
             * Thoi gian
             */
            if(isset($post['tuNgay']) && $post['tuNgay'] != ''){
                $qb->andWhere('cv.ngayHoanThanh >= ?3');
                $qb->setParameter(3, $post['tuNgay']);
            }
            if(isset($post['denNgay']) && $post['denNgay'] != ''){
                $qb->andWhere('cv.ngayHoanThanh <= ?4');
                $qb->setParameter(4, $post['denNgay']);
            }

            /**
             * Trang thai
             */
            switch ($post['trangThai']) {
                case '1':
                    // chua hoan thanh
                    $qb->andWhere('cv.trangThai in (?5)');
                    $qb->setParameter(5, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY));
                    break;
                case '2':
                    // da hoan thanh
                    $qb->andWhere('cv.trangThai in (?6)');
                    $qb->setParameter(6, array(\CongViec\Entity\CongViec::HOAN_THANH, \CongViec\Entity\CongViec::TRE_HAN));
                    break;
                case '3':
                    // qua han
                    $qb->andWhere('cv.ngayHoanThanh <= ?7');
                    $date = new DateTime('now');
                    $qb->setParameter(7, $date->format('Y-m-d H:i:s'));
                    break;
                case '4':
                    // tat ca
                    break;
            }

            /**
             * Tim nhanh
             */
            if(isset($post['tuKhoa']) && $post['tuKhoa'] != '' ){
                switch ($post['tieuChi']) {
                    case '1':
                        // tim theo chu de
                        $qb->andWhere('cv.ten like ?8');
                        $qb->setParameter(8, '%'.$post['tuKhoa'].'%');
                        break;
                    case '2':
                        // tim theo ten nguoi ky
                        $qb->andWhere('CONCAT(nk.ho, \' \', nk.ten) like ?9');
                        $qb->setParameter(9, '%'.$post['tuKhoa'].'%');
                        break;
                    case '3':
                        // tim theo trich yeu
                        $qb->andWhere('c.trichYeu like ?10');
                        $qb->setParameter(10, '%'.$post['tuKhoa'].'%');
                        break;
                    case '4':
                        // tim theo so hieu
                        $qb->andWhere('c.soHieu like ?11');
                        $qb->setParameter(11, '%'.$post['tuKhoa'].'%');
                        break;
                }
            }

            $form->setData($post);
        }
        
        //var_dump($qb->getDql());
        /*$query = $qb->getQuery();
        $congViecs = $query->getResult();*/
        $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);     
        $page = (int)$this->params('page');
        if($page) 
            $paginator->setCurrentPageNumber($page);

        return array(
            'form' => $form,
            'congViecs'=>$paginator,
            'congViecService' => $this->getServiceLocator()->get('cong_viec')
        );
    }

    public function congViecDonViAction(){
        if(!$this->zfcUserAuthentication()->hasIdentity()) {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        else {
            $user = $this->zfcUserAuthentication()->getIdentity();
            $donVi = $user->getDonVi();
            if(!$donVi) throw new \Exception("Không tìm thấy đơn vị");
        }

        $entityManager=$this->getEntityManager(); 
        $form = new LocCongViecDonViForm($entityManager, $donVi);

        $request=$this->getRequest();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('cv')
            ->from('CongViec\Entity\CongViec', 'cv')
            ->join('cv.nguoiThucHiens', 'pc')
            ->leftJoin('cv.cha', 'c')
            ->leftJoin('c.nguoiKy', 'nk')
            ->leftJoin('cv.donViTiepNhans', 'dv')
            ->where('dv.id = ?1')
            ->setParameter(1, $donVi->getId())
            ->orderBy('cv.ngayTao', 'DESC')
            ;

        if($request->isPost()){
            $post = $request->getPost();

            /**
             * Thoi gian
             */
            if(isset($post['tuNgay']) && $post['tuNgay'] != ''){
                $qb->andWhere('cv.ngayHoanThanh >= ?3');
                $qb->setParameter(3, $post['tuNgay']);
            }
            if(isset($post['denNgay']) && $post['denNgay'] != ''){
                $qb->andWhere('cv.ngayHoanThanh <= ?4');
                $qb->setParameter(4, $post['denNgay']);
            }

            /**
             * Trang thai
             */
            switch ($post['trangThai']) {
                case '1':
                    // chua hoan thanh
                    $qb->andWhere('cv.trangThai in (?5)');
                    $qb->setParameter(5, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY));
                    break;
                case '2':
                    // da hoan thanh
                    $qb->andWhere('cv.trangThai in (?6)');
                    $qb->setParameter(6, array(\CongViec\Entity\CongViec::HOAN_THANH, \CongViec\Entity\CongViec::TRE_HAN));
                    break;
                case '3':
                    // qua han
                    $qb->andWhere('cv.ngayHoanThanh <= ?7');
                    $date = new DateTime('now');
                    $qb->setParameter(7, $date->format('Y-m-d H:i:s'));
                    break;
                case '4':
                    // tat ca
                    break;
            }

            /**
             * Tim nhanh
             */
            if(isset($post['tuKhoa']) && $post['tuKhoa'] != '' ){
                if($post['tieuChi'] == 1){
                    // tim theo chu de
                    $qb->andWhere('cv.ten like ?8');
                    $qb->setParameter(8, '%'.$post['tuKhoa'].'%');
                }
                else{
                    // tim theo ten nguoi ky
                    $qb->andWhere('CONCAT(nk.ho, \' \', nk.ten) like ?9');
                    $qb->setParameter(9, '%'.$post['tuKhoa'].'%');
                }
            }

            $form->setData($post);
        }
        
        //var_dump($qb->getDql());
        /*$query = $qb->getQuery();
        $congViecs = $query->getResult();*/
        $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);     
        $page = (int)$this->params('page');
        if($page) 
            $paginator->setCurrentPageNumber($page);

        return array(
            'form' => $form,
            'congViecs'=>$paginator,
            'congViecService' => $this->getServiceLocator()->get('cong_viec')
        );
    }
}