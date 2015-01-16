<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;

class QuyenFilter extends InputFilter
{
    public function __construct()
    {

        $this->add(array(
            'name'       => 'roleId',
            'required'   => true,
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'roleName',
            'required'   => true,
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'parent',
            'required'   => false,
        ));
    }
}
