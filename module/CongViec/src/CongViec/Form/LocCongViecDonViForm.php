<?php
namespace CongViec\Form;

use Zend\Form\Form;
use DateTime;

class LocCongViecDonViForm extends Form
{

    public function __construct($entityManager, $donVi)
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
                'value_options' => $this->getTrangThaiOptions($entityManager, $donVi)
            ),
            'attributes' => array(
                'value' => '4'
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
                    '2' => 'Người ký',
                    '3' => 'Trích yếu',
                    '4' => 'Số hiệu'
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

    public function getTrangThaiOptions($entityManager, $donVi){
        
        $countTatCa = $this->countTatCa($entityManager, $donVi);
        $countChuaHoanThanh = $this->countChuaHoanThanh($entityManager, $donVi);
        $countHoanThanh = $this->countHoanThanh($entityManager, $donVi);
        $countQuaHan = $this->countQuaHan($entityManager, $donVi);

        return array(
            '1' => 'Chưa hoàn thành ['.$countChuaHoanThanh.']',
            '2' => 'Đã hoàn thành ['.$countHoanThanh.']',
            '3' => 'Công việc bị quá hạn ['.$countQuaHan.']',
            '4' => 'Tất cả các công việc ['.$countTatCa.']'
        );
    }

    public function chung($entityManager, $donVi){
        $qb = $entityManager->createQueryBuilder();
        $qb->select('count(distinct cv.id)')
            ->from('CongViec\Entity\CongViec', 'cv')
            ->join('cv.nguoiThucHiens', 'pc')
            ->leftJoin('cv.cha', 'c')
            ->leftJoin('c.nguoiKy', 'nk')
            ->leftJoin('cv.donViTiepNhans', 'dv')
            ->where('dv.id = ?1')
            ->setParameter(1, $donVi->getId())
            ;
        return $qb;
    }

    public function countTatCa($entityManager, $donVi){
        $qb = $this->chung($entityManager, $donVi);
        return $qb->getQuery()->getSingleScalarResult();
       // var_dump($qb->getDql());
    }

    public function countChuaHoanThanh($entityManager, $donVi){
        $qb = $this->chung($entityManager, $donVi);
        $qb->andWhere('cv.trangThai in (?5)');
        $qb->setParameter(5, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY));
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countHoanThanh($entityManager, $donVi){
        $qb = $this->chung($entityManager, $donVi);
        $qb->andWhere('cv.trangThai in (?6)');
        $qb->setParameter(6, array(\CongViec\Entity\CongViec::HOAN_THANH, \CongViec\Entity\CongViec::TRE_HAN));
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countQuaHan($entityManager, $donVi){
        $qb = $this->chung($entityManager, $donVi);
        $qb->andWhere('cv.ngayHoanThanh <= ?7');
        $date = new DateTime('now');
        $qb->setParameter(7, $date->format('Y-m-d H:i:s'));
        return $qb->getQuery()->getSingleScalarResult();
    }
}