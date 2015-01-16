<?php namespace CongViec\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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

class KetXuatController extends AbstractActionController
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

    }

    public function inDanhSachCongViecAction(){      
        $entityManager=$this->getEntityManager();
        if(!$this->zfcUserAuthentication()->hasIdentity()) {
           return $this->redirect()->toRoute('zfcuser/login',array('action'=>'login'));
        }
        else {
            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
        }
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
        }
        
        //var_dump($qb->getDql());
        $query = $qb->getQuery();
        $congViecs = $query->getResult();
        //die(var_dump($congViecs));

        $objPHPExcel = new PHPExcel();                
                $fileName='bao_cao';
                $tieuDe='NHẬT KÝ CÔNG VIỆC';                
                $fieldName=array(0=>'STT',1=>'Số ký hiệu văn bản, người ký',2=>'Nội dung được giao',3=>'Cơ quan chủ công thực hiện/ Người chủ trì',4=>'Thời gian hoàn thành',5=>'Kết quả thực hiện');
                $PI_ExportExcel=$this->ExportExcel();
                $exportExcel=$PI_ExportExcel->exportExcel($objPHPExcel, $fileName, $this->data($objPHPExcel, $tieuDe, $fieldName,$congViecs));
    }

    public function data($objPHPExcel, $tieuDe, $fieldName,$congViecs){
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

    public function inCongViecAction(){
        $entityManager=$this->getEntityManager();
    	$idCongViec = (int) $this->params()->fromRoute('id', 0);
        if(!$idCongViec) die('Lỗi không tìm thấy công việc');

        $entityManager = $this->getEntityManager();
        $congViec = $entityManager->getRepository('CongViec\Entity\CongViec')->find($idCongViec);
        $objPHPExcel = new PHPExcel();                
            $fileName='bao_cao_qua_trinh';
            $tieuDe='DANH SÁCH BÁO CÁO QUÁ TRÌNH THỰC HIỆN CÔNG VIỆC';                
            $fieldName=array(0=>'Tên công việc',1=>'STT',2=>'Nội dung',3=>'Ngày báo cáo', 4=>'Người tạo báo cáo');
            $PI_ExportExcel=$this->ExportExcel();
            $exportExcel=$PI_ExportExcel->exportExcel($objPHPExcel, $fileName, $this->dataBaoCaoQuaTrinh($objPHPExcel, $tieuDe, $fieldName,$congViec));
    }

    public function dataBaoCaoQuaTrinh($objPHPExcel, $tieuDe, $fieldName,$congViec)
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

        $congVan = $congViec->getCongVan();
        $trangThai=$congVan->getTrangThai();
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Sóo hiệu: '.$congVan->getSoHieu());
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Ngày Ban Hành: '.$congViec->getNgayBanHanh()->format('d/m/Y'));
        $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Hạn Xử Lý: '.$congViec->getNgayHoanThanh()->format('d/m/Y'));

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
        $theoDois = $congViec->getBaoCaos();
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
    }
    
}