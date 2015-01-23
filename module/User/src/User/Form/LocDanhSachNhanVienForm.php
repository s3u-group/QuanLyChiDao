<?php
namespace User\Form;

use Zend\Form\Form;

class LocDanhSachNhanVienForm extends Form
{

    public function __construct()
    {
        parent::__construct('loc-form');

        $this->setAttribute('method', 'post')
            ->setAttribute('id', 'locForm')
        ;

        $this->add(array(
            'name' => 'tuKhoa',
            'type' => 'text',
            'options' => array(
                'label' => 'Từ khóa'
            ),
            'attributes' => array(
                'placeholder' => 'Nhập từ khóa tìm kiếm...',
                'style' => 'width:300px'
            )
        ));

        $this->add(array(
            'name' => 'tieuChi',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    '1' => 'Họ tên',
                    '2' => 'Điện thoại',
                    '3' => 'Chức vụ'
                )
            ),
            'attributes' => array(
                'class' => 'ui pointing dropdown link item',
            )
        ));

        $this->add(array(
            'name' => 'donVi',
            'type' => 'select',
            'options' => array(
                'label' => 'Đơn vị'
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