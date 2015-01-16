<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

use User\Entity\Role;
use User\Form\QuyenFilter;

class QuyenForm extends Form
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('quyen-form');

        $this
            ->setAttribute('method', 'post')
            ->setAttribute('autocomplete', 'off')
            ->setHydrator(new DoctrineHydrator($objectManager))
            ->setObject(new Role())
            ->setInputFilter(new QuyenFilter())
        ;

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'roleId',
            'attributes' => array(

            ),
            'options' => array(
                'label' => 'Mã quyền'
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'roleName',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Tên quyền'
            )
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'parent',
            'attributes' => array(
                'class' => 'ui dropdown'
            ),
            'options' => array(
                'label' => 'Quyền cha',
                'value_options' => $this->getQuyenChaOptions($objectManager),
                'empty_option' => '-- Quyền cha --'
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
                'value' => 'Tạo quyền',
                'class' => 'ui blue button',
            ),
        ));
    }

    public function getQuyenChaOptions($objectManager){
        $options = array();
        $query = $objectManager->createQuery('select r from User\Entity\Role r');
        $roles = $query->getResult();
        foreach($roles as $role){
            $options[$role->getId()] = $role->getRoleName();
        }
        return $options;
    }
}