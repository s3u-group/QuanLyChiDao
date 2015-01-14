<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

use User\Form\CreateAccountFilter;

class CreateAccountForm extends Form
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('create-account-form');

        $this
            ->setAttribute('method', 'post')
            ->setAttribute('autocomplete', 'off')
            ->setHydrator(new DoctrineHydrator($objectManager))
            ->setInputFilter(new CreateAccountFilter())
        ;

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'username',
            'attributes' => array(

            ),
            'options' => array(
                'label' => 'Tên đăng nhập'
            )
        ));

        $this->add(array(
            'type' => 'password',
            'name' => 'password',
            'attributes' => array(
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'Mật khẩu'
            )
        ));

        $this->add(array(
            'type' => 'password',
            'name' => 'passwordVerify',
            'attributes' => array(
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'Xác nhận mật khẩu'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Tạo tài khoản',
                'class' => 'ui blue button',
            ),
        ));
    }
}