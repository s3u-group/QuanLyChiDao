<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use CongViec\Entity\CongVan;
use CongViec\Entity\CongViec;
use CongViec\Entity\PhanCong;
use CongViec\Entity\DinhKem;
use CongViec\Form\GiaoViecForm;
use CongViec\Form\CapNhatCongViecForm;
use DateTime;
use DateTimeZone;

use CongViec\Form\LocCanXuLyForm;
use CongViec\Form\LocNhatKyForm;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel5;
use PHPExcel_Cell;
use PHPExcel_Cell_DataType;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Style_Color;
use PHPExcel_RichText;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Font;

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
        if(!$this->zfcUserAuthentication()->hasIdentity()) {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        else {
            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
        }

        $entityManager=$this->getEntityManager();  

        $form = new LocCanXuLyForm($entityManager, $userId);

        $request=$this->getRequest();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('cv')
            ->from('CongViec\Entity\CongViec', 'cv')
            ->join('cv.nguoiThucHiens', 'pc', 'with', 'pc.nguoiThucHien = ?1')
            ->leftJoin('cv.cha', 'c')
            ->leftJoin('c.nguoiKy', 'nk')
            ->where('cv.trangThai in (?2)')
            ->andWhere('pc.vaiTro != ?50')
            ->setParameter(1, $userId)
            ->setParameter(2, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY))
            ->setParameter(50, \CongViec\Entity\PhanCong::NGUOI_PHAN_CONG)
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
                    // chua xem
                    $qb->andWhere('pc.trangThai = ?5');
                    $qb->setParameter(5, \CongViec\Entity\PhanCong::CHUA_XEM);
                    break;
                case '2':
                    // dang xu ly
                    $qb->andWhere('pc.trangThai != ?6');
                    $qb->setParameter(6, \CongViec\Entity\PhanCong::CHUA_XEM);
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
        else{
            //mac dinh chi hien chua xem
            $qb->andWhere('pc.trangThai = ?10');
            $qb->setParameter(10, \CongViec\Entity\PhanCong::CHUA_XEM);
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
    
    public function nhatKyCongViecAction(){
        if(!$this->zfcUserAuthentication()->hasIdentity()) {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }

        $entityManager=$this->getEntityManager();  

        $form = new LocNhatKyForm();

        $request=$this->getRequest();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('cv')
            ->from('CongViec\Entity\CongViec', 'cv')
            ->join('cv.nguoiThucHiens', 'pc')
            ->leftJoin('cv.cha', 'c')
            ->leftJoin('c.nguoiKy', 'nk')
            ->where('cv.trangThai in (?2)')
            ->andWhere('pc.vaiTro != ?50')
            ->setParameter(2, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY))
            ->setParameter(50, \CongViec\Entity\PhanCong::NGUOI_PHAN_CONG)
            ->orderBy('cv.id', 'DESC')
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
        $query = $qb->getQuery();
        $congViecs = $query->getResult();

        return array(
            'form' => $form,
            'congViecs'=>$congViecs,
            'congViecService' => $this->getServiceLocator()->get('cong_viec')
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

                $congViec = $this->xuLyNguoiGiaoViec($congViec);
                $congViec = $this->xuLyDonViTiepNhan($congViec);

                $entityManager->persist($congViec);
                $entityManager->flush();

                $post = $post['congViec']['dinhKems'];
                $this->dinhKemMoi($entityManager, $post, $congViec);

                $form->bind($congViec);
                $this->flashMessenger()->addSuccessMessage('Bạn vừa thêm đơn vị mới thành công!');
            }
        }

        return array(
            'form' => $form,
        );
    }

    public function xuLyNguoiGiaoViec($congViec){
        $nguoiKy = $congViec->getCongVan()->getNguoiKy();
        $nguoiTao = $congViec->getNguoiTao();
        $nguoiThucHiens = array();

        $pcNguoiGiaoViec = new PhanCong();
        $pcNguoiGiaoViec->setVaiTro(\CongViec\Entity\PhanCong::NGUOI_PHAN_CONG);
        $pcNguoiGiaoViec->setNguoiThucHien($nguoiKy);
        $nguoiThucHiens[] = $pcNguoiGiaoViec;

        if($nguoiTao->getId() != $nguoiKy->getId()){
            $pcNguoiGiaoViec = new PhanCong();
            $pcNguoiGiaoViec->setVaiTro(\CongViec\Entity\PhanCong::NGUOI_PHAN_CONG);
            $pcNguoiGiaoViec->setNguoiThucHien($nguoiTao);
            $nguoiThucHiens[] = $pcNguoiGiaoViec;
        }

        $congViec->addNguoiThucHiens($nguoiThucHiens);
        return $congViec;
    }

    public function xuLyDonViTiepNhan($congViec){
        $users = $congViec->getNguoiThucHiens();
        $donViIds = array();
        foreach($users as $user){
            $donVi = $user->getNguoiThucHien()->getDonVi();
            if(!in_array($donVi->getId(), $donViIds)){
                $congViec->addDonViTiepNhans(array($donVi));
                $donViIds[] = $donVi->getId();
            }
        }
        return $congViec;
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
            $query->setParameter('id', $id);
            $nhanVien = $query->getResult();
            $response = array(
                'id' => $id,
                'ten' => $nhanVien->getHoTen()
            );
            $json = new JsonModel($response);
            return $json;
        }
    }

    public function xemCongViecAction(){
        $idCongViec = (int) $this->params()->fromRoute('id', 0);
        if(!$idCongViec) return $this->redirect()->toRoute('theo_doi');

        $entityManager = $this->getEntityManager();
        $congViecService = $this->getServiceLocator()->get('cong_viec');
        //$congViec = $entityManager->getRepository('CongViec\Entity\CongViec')->find($idCongViec);
        $query = $entityManager->createQuery('select c, b from CongViec\Entity\CongViec c left join c.baoCaos b with b.trangThai != ?2 where c.id = ?1 order by b.ngayBaoCao DESC');
        $query->setParameter(1, $idCongViec);
        $query->setParameter(2, \CongViec\Entity\TheoDoi::DA_HUY);
        $congViec = $query->getOneOrNullResult();

        if(!$congViecService->duocPhanCong($congViec)) die('Bạn không thể xem nội dung này');
        
        /* cap nhat trang thai */
        if($congViec->isChuaXem()){
            $congViec->setTrangThai(\CongViec\Entity\CongViec::DANG_XU_LY);
            $entityManager->flush();
        }
        $congViecService->moCongViec($congViec); // thay doi trang thai trong phan cong
        
        return array(
            'congViec' => $congViec,
            'congViecService' => $congViecService
        );
    }

    public function chiTietCongViecAction()
    {
        $idCongViec = (int) $this->params()->fromRoute('id', 0);
        if(!$idCongViec) return $this->redirect()->toRoute('theo_doi');

        $entityManager = $this->getEntityManager();
        //$congViec = $entityManager->getRepository('CongViec\Entity\CongViec')->find($idCongViec);
        $query = $entityManager->createQuery('select c, b from CongViec\Entity\CongViec c left join c.baoCaos b with b.trangThai != ?2 where c.id = ?1 order by b.ngayBaoCao DESC');
        $query->setParameter(1, $idCongViec);
        $query->setParameter(2, \CongViec\Entity\TheoDoi::DA_HUY);
        $congViec = $query->getOneOrNullResult();
        
        return array(
            'congViec' => $congViec,
            'congViecService' => $this->getServiceLocator()->get('cong_viec')
        );
    }

    public function huyCongViecAction(){
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()){
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Không tìm thấy công việc'
                );
            }
            else{
                $entityManager = $this->getEntityManager();
                $user = $this->zfcUserAuthentication()->getIdentity();
                $congViec = $entityManager->getRepository('CongViec\Entity\CongViec')->find($id);
                $service = $this->getServiceLocator()->get('cong_viec');
                if($service->daGiao($congViec)){
                    $congViec->setTrangThai(\CongViec\Entity\CongViec::DA_HUY);
                    $entityManager->flush();
                    $response = array(
                        'status' => 'success',
                        'message' => 'Công việc đã hủy'
                    );
                }
                else{
                    $response = array(
                        'status' => 'error',
                        'message' => 'Bạn không thể hủy công việc này'
                    );
                }
            }
            $json = new JsonModel($response);
            return $json;
        }
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

    /*public function xuatBaoCaoAction()
    {        
        $entityManager=$this->getEntityManager();
        $request=$this->getRequest();
        if($request->isPost())
        {
            if($request->getPost()->get('mangId'))
            {
                $mangIds=$request->getPost()->get('mangId');
                $mang='';                
                $i = 0;
                $len = count($mangIds);
                foreach ($mangIds as $mangId) {
                    $mang.=''.$mangId.'';
                    if ($i != $len - 1)
                    {
                        $mang.=',';
                    }
                    $i++;
                }                
                $query=$entityManager->createQuery('SELECT cv FROM CongViec\Entity\CongViec cv, CongViec\Entity\PhanCong pc WHERE cv.id IN ('.$mang.')');
                $congViecs=$query->getResult();                
                $objPHPExcel = new PHPExcel();                
                $fileName='bao_cao';
                $tieuDe='NHẬT KÝ CÔNG VIỆC';                
                $fieldName=array(0=>'STT',1=>'Số ký hiệu văn bản, người ký',2=>'Nội dung được giao',3=>'Cơ quan chủ công thực hiện/ Người chủ trì',4=>'Thời gian hoàn thành',5=>'Kết quả thực hiện');
                $PI_ExportExcel=$this->ExportExcel();
                $exportExcel=$PI_ExportExcel->exportExcel($objPHPExcel, $fileName, $this->data($objPHPExcel, $tieuDe, $fieldName,$congViecs));
            }
            else
            {
                //Không có dữ liệu để xuất
            }
        }
        return $this->redirect()->toRoute('cong_viec/crud',array('action'=>'nhat-ky-cong-viec'));
    }

    public function data($objPHPExcel, $tieuDe, $fieldName,$congViecs)
    {
        $entityManager=$this->getEntityManager();        
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);

        $objPHPExcel->getActiveSheet()->setCellValue('A1', $tieuDe);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');        
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->setCellValue('A2', $fieldName[0])
                                      ->setCellValue('B2', $fieldName[1])
                                      ->setCellValue('C2', $fieldName[2])
                                      ->setCellValue('D2', $fieldName[3])
                                      ->setCellValue('E2', $fieldName[4])
                                      ->setCellValue('F2', $fieldName[5])
                                      ->getStyle('A2:F2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        foreach(array('A','B','D','E','F') as $columnID) {
            $objPHPExcel->getActiveSheet()->getStyle($columnID)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        foreach ($congViecs as $index => $congViec) {            
            $nguoiThucHiens=$congViec->getNguoiThucHiens();
            foreach ($nguoiThucHiens as $nguoiThucHien)
            {
                if($nguoiThucHien->getVaiTro()==4)
                {                    
                    $chuTri=$nguoiThucHien->getNguoiThucHien()->getHoTen();
                }                
            }
            $dong=$index+3;            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$dong, $index+1);            
            $objPHPExcel->getActiveSheet()->getStyle('B'.$dong)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$dong, 'Số '.$congViec->getCha()->getSoHieu().'ngày '."\n".$congViec->getCha()->getNguoiKy()->getHoTen());

            $objPHPExcel->getActiveSheet()->setCellValue('C'.$dong,$congViec->getNoiDung());
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$dong,$chuTri);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$dong, date_format($congViec->getNgayHoanThanh(), 'd-m-Y'));
            if($congViec->getTrangThai()==0)
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$dong, 'Đã hủy');
            }
            if($congViec->getTrangThai()==1)
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$dong, 'Chưa xem');
            }
            if($congViec->getTrangThai()==5)
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$dong, 'Đang xử lý');
            }
            if($congViec->getTrangThai()==10)
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$dong, 'Hoàn thành');
            }
            if($congViec->getTrangThai()==15)
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$dong, 'Trễ hạn');
            }
            $objPHPExcel->getActiveSheet()->getStyle('A'.$dong.':F'.$dong)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$dong.':F'.$dong)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        }
        foreach(range('A','F') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }        
    }

    public function xuatBaoCaoQuaTrinhAction()
    {
        $entityManager=$this->getEntityManager();
        $request=$this->getRequest();
        if($request->isPost())
        {            
            $mangIds=$request->getPost()->get('mangId');
            $mang='';                
            $i = 0;
            $len = count($mangIds);
            foreach ($mangIds as $mangId) {
                $mang.=''.$mangId.'';
                if ($i != $len - 1)
                {
                    $mang.=',';
                }
                $i++;
            }

            $query=$entityManager->createQuery('SELECT td FROM CongViec\Entity\TheoDoi td WHERE td.id IN ('.$mang.')');
            $theoDois=$query->getResult();

            $objPHPExcel = new PHPExcel();                
            $fileName='bao_cao_qua_trinh';
            $tieuDe='DANH SÁCH BÁO CÁO QUÁ TRÌNH THỰC HIỆN CÔNG VIỆC';                
            $fieldName=array(0=>'Tên công việc',1=>'STT',2=>'Nội dung',3=>'Ngày báo cáo', 4=>'Người tạo báo cáo');
            $PI_ExportExcel=$this->ExportExcel();
            $exportExcel=$PI_ExportExcel->exportExcel($objPHPExcel, $fileName, $this->dataBaoCaoQuaTrinh($objPHPExcel, $tieuDe, $fieldName,$theoDois));
        }        
    }

    public function dataBaoCaoQuaTrinh($objPHPExcel, $tieuDe, $fieldName,$theoDois)
    {
        $entityManager=$this->getEntityManager();        
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', $tieuDe);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:D4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', $fieldName[0])
                                      ->setCellValue('A4', $fieldName[1])
                                      ->setCellValue('B4', $fieldName[2])
                                      ->setCellValue('C4', $fieldName[3])
                                      ->setCellValue('D4', $fieldName[4])
                                      ->getStyle('A2:D2')->getFont()->setBold(true);
        $trangThai=$theoDois[0]->getCongVan()->getTrangThai();
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Tên: '.$theoDois[0]->getCongVan()->getTen());
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Ngày Ban Hành: '.$theoDois[0]->getCongVan()->getNgayBanHanh()->format('d/m/Y'));
        $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Hạn Xử Lý: '.$theoDois[0]->getCongVan()->getNgayHoanThanh()->format('d/m/Y'));

        if($trangThai==0)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Trạng thái: Đã hủy');
        }
        if($trangThai==1)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Trạng thái: Chưa xem');
        }
        if($trangThai==5)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Trạng thái: Đang xử lý');
        }
        if($trangThai==10)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Trạng thái: Hoàn thành');
        }
        if($trangThai==15)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Trạng thái: Trễ hạn');
        }
        foreach(array('A','C','D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getStyle($columnID)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        foreach ($theoDois as $index => $theoDoi) {
            $dong=$index+5;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$dong, $index+1);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$dong, $theoDoi->getNoiDung());
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$dong, $theoDoi->getNgayBaoCao()->format('d/m/Y'));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$dong, $theoDoi->getNguoiTao()->getHoTen());

            $objPHPExcel->getActiveSheet()->getStyle('A'.$dong.':D'.$dong)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$dong.':D'.$dong)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        }
        foreach(range('A','D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
    }*/
}