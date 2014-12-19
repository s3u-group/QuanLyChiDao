<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use User\Entity\User;

class UserFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        parent::__construct('user');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new User())
        ;

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
            ),
        ));

        $this->add(array(
            'name' => 'ho',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Họ'
            ),
            'options' => array(
                'label' => 'Họ'
            )
        ));

        $this->add(array(
            'name' => 'ten',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Tên'
            ),
            'options' => array(
                'label' => 'Tên'
            )
        ));

        $this->add(array(
            'name' => 'gioiTinh',
            'type' => 'hidden',
            'attributes' => array(

            ),
            'options' => array(
                'label' => 'Giới tính'
            )
        ));

        $this->add(array(
            'name' => 'diaChi',
            'type' => 'text',
            'options' => array(
                'label' => 'Địa chỉ',
            ),
            'attributes' => array(
            ),
        ));

        $this->add(array(
            'name' => 'thanhPho',
            'type' => 'select',
            'attributes' => array(

            ),
            'options' => array(
                'label' => 'Thành phố'
            )
        ));

        $this->add(array(
            'name' => 'quocGia',
            'type' => 'select',
            'attributes' => array(

            ),
            'options' => array(
                'label' => 'Quốc gia'
            )
        ));

        $dienThoai = new \User\Form\Element\Phone();
        $dienThoai->setEntityManager($objectManager);
        $dienThoai->setName('dienThoai');
        $dienThoai->setOptions(array(
            'label' => 'Điện thoại'
        ));
        $this->add($dienThoai);   

        $this->add(array(
            'name' => 'state',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Hoạt động',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'attributes' => array(
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'ho' => array(
                'required' => true
            ),
            'ten' => array(
                'required' => true
            ),
            'dienThoai' => array(
                'required' => true
            ),
            'email' => array(
                'required' => true
            ),
            'state' => array(
                'required' => false
            ),
            'thanhPho' => array(
                'required' => false
            ),
            'quocGia' => array(
                'required' => false
            )
        );
    }

}