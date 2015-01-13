<?php
namespace CongViec\Form;

use Zend\Form\Form;

class LocCanXuLyForm extends Form
{

    public function __construct()
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
                'value_options' => array(
                    '1' => 'Công việc chưa xử lý',
                    '2' => 'Công việc đang xử lý',
                    '3' => 'Công việc bị quá hạn',
                    '4' => 'Tất cả các công việc'
                )
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
                'style' => 'width:350px'
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
}