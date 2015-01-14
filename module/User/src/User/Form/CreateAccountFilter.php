<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;

class CreateAccountFilter extends InputFilter
{
    public function __construct()
    {

        $this->add(array(
            'name'       => 'username',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                        'message'=> 'Tên đăng nhập quá ngắn, ít nhất phải %min% ký tự'
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'password',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                        'message'=> 'Mật khẩu quá ngắn, ít nhất phải %min% ký tự'
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'passwordVerify',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                        'message'=> 'Tên đăng nhập quá ngắn, ít nhất phải %min% ký tự'
                    ),
                ),
                array(
                    'name' => 'identical',
                    'options' => array(
                        'token' => 'password',
                        'message'=> 'Hai lần nhập mật khẩu không trùng khớp nhau'
                    )
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}
