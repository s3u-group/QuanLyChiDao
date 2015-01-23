<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

use User\Form\UserFieldset;

class UpdateUserForm extends Form
{

    public function __construct(ObjectManager $objectManager, $sm)
    {
        parent::__construct('update-user-form');

        $this
            ->setAttribute('method', 'post')
            ->setHydrator(new DoctrineHydrator($objectManager))
        ;

        $userFieldset = new UserFieldset($objectManager, $sm);
        $userFieldset->setUseAsBaseFieldset(true);
        $this->add($userFieldset);

        /*$this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));*/

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Lưu thông tin',
                'class' => 'ui blue button',
            ),
        ));
    }
}