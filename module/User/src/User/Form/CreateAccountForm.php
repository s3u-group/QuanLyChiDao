<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class CreateAccountForm extends Form
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('create-account-form');

        $this
            ->setAttribute('method', 'post')
            ->setAttribute('autocomplete', 'off')
            ->setHydrator(new DoctrineHydrator($objectManager));        

        /*$this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));*/

        $userFieldset = new UserFieldset($objectManager);
        $userFieldset->setUseAsBaseFieldset(true);
        $this->add($userFieldset);

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Tạo tài khoản',
                'class' => 'ui submit button primary',
            ),
        ));
    }
}