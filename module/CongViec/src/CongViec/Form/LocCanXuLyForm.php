<?php
namespace CongViec\Form;

use Zend\Form\Form;
use DateTime;

class LocCanXuLyForm extends Form
{

    public function __construct($entityManager, $userId)
    {
        parent::__construct('loc-form');

        $this->setAttribute('method', 'post')
            ->setAttribute('id', 'locForm')
        ;

        $this->add(array(
            'name' => 'tuNgay',
            'type' => 'date',
            'options' => array(
                'label' => 'Từ ngày'
            ),
            'attributes' => array(
                'width' => '130px'
            )
        ));

        $this->add(array(
            'name' => 'denNgay',
            'type' => 'date',
            'options' => array(
                'label' => 'Đến ngày'
            ),
            'attributes' => array(
                'width' => '130px'
            )
        ));

        $this->add(array(
            'name' => 'trangThai',
            'type' => 'radio',
            'options' => array(
                'value_options' => $this->getTrangThaiOptions($entityManager, $userId)
            ),
            'attributes' => array(
                'value' => '1'
            )
        ));

        $this->add(array(
            'name' => 'tuKhoa',
            'type' => 'text',
            'options' => array(
                'label' => 'Từ khóa'
            ),
            'attributes' => array(
                'placeholder' => 'Nhập từ khóa tìm kiếm...',
                'style' => 'width:340px'
            )
        ));

        $this->add(array(
            'name' => 'tieuChi',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    '1' => 'Chủ đề',
                    '2' => 'Người ký'
                )
            ),
            'attributes' => array(
                'class' => 'ui pointing dropdown link item',
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Lưu',
            ),
        ));
    }

    public function getTrangThaiOptions($entityManager, $userId){
        
        $countTatCa = $this->countTatCa($entityManager, $userId);
        $countChuaXem = $this->countChuaXem($entityManager, $userId);
        $countDangXuLy = $this->countDangXuLy($entityManager, $userId);
        $countQuaHan = $this->countQuaHan($entityManager, $userId);

        return array(
            '1' => 'Công việc chưa xem ['.$countChuaXem.']',
            '2' => 'Công việc đang xử lý ['.$countDangXuLy.']',
            '3' => 'Công việc bị quá hạn ['.$countQuaHan.']',
            '4' => 'Tất cả các công việc ['.$countTatCa.']'
        );
    }

    public function chung($entityManager, $userId){
        $qb = $entityManager->createQueryBuilder();
        $qb->select('count(distinct cv.id)')
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
        return $qb;
    }

    public function countTatCa($entityManager, $userId){
        $qb = $this->chung($entityManager, $userId);
        return $qb->getQuery()->getSingleScalarResult();
       // var_dump($qb->getDql());
    }

    public function countChuaXem($entityManager, $userId){
        $qb = $this->chung($entityManager, $userId);
        $qb->andWhere('pc.trangThai = ?5');
        $qb->setParameter(5, \CongViec\Entity\PhanCong::CHUA_XEM);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countDangXuLy($entityManager, $userId){
        $qb = $this->chung($entityManager, $userId);
        $qb->andWhere('pc.trangThai != ?6');
        $qb->setParameter(6, \CongViec\Entity\PhanCong::CHUA_XEM);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countQuaHan($entityManager, $userId){
        $qb = $this->chung($entityManager, $userId);
        $qb->andWhere('cv.ngayHoanThanh <= ?7');
        $date = new DateTime('now');
        $qb->setParameter(7, $date->format('Y-m-d H:i:s'));
        return $qb->getQuery()->getSingleScalarResult();
    }
}