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

use CongViec\Form\LocForm;

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

        $form = new LocForm();

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
                    // chua xu ly
                    $qb->andWhere('cv.trangThai = ?5');
                    $qb->setParameter(5, \CongViec\Entity\CongViec::CHUA_XEM);
                    break;
                case '2':
                    // dang xu ly
                    $qb->andWhere('cv.trangThai = ?6');
                    $qb->setParameter(6, \CongViec\Entity\CongViec::DANG_XU_LY);
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
        $query = $qb->getQuery();
        $congViecs = $query->getResult();

        return array(
            'form' => $form,
            'congViecs'=>$congViecs,
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

    public function xuatBaoCaoAction()
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
                $tieuDe='BẢNG CẬP NHẬT CÔNG VĂN CHỈ ĐẠO GIAO CÁC PHÒNG BAN THÀNH PHỐ';                
                $fieldName=array(0=>'STT',1=>'Số ký hiệu văn bản, người ký',2=>'Nội dung được giao',3=>'Cơ quan chủ công thực hiện',4=>'Thời gian hoàn thành',5=>'Kết quả thực hiện-Tình trạng xử lý');
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
        if(!$this->zfcUserAuthentication()->hasIdentity())
        {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        else
        {
            $idUser=$this->zfcUserAuthentication()->getIdentity()->getId();
        }        
        $entityManager=$this->getEntityManager();
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', $tieuDe);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->setCellValue('A2', $fieldName[0])
                                      ->setCellValue('B2', $fieldName[1])
                                      ->setCellValue('C2', $fieldName[2])
                                      ->setCellValue('D2', $fieldName[3])
                                      ->setCellValue('E2', $fieldName[4])
                                      ->setCellValue('F2', $fieldName[5])
                                      ->getStyle('A2:F2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        foreach ($congViecs as $index => $congViec) {            
            $dong=$index+3;            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$dong, $index+1);            
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$dong, $congViec->getSoHieu());

            $objPHPExcel->getActiveSheet()->getStyle('B'.$dong)->getAlignment()->setWrapText(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C'.$dong,$congViec->getNoiDung());
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$dong,'');
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
        }
    }
}