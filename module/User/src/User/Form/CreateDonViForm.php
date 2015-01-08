<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class CreateDonViForm extends Form
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('create-don-vi-form');

        $this
            ->setAttribute('method', 'post')
            ->setAttribute('autocomplete', 'off')
            ->setHydrator(new DoctrineHydrator($objectManager));        

        /*$this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));*/

        $donViFieldset = new DonViFieldset($objectManager);
        $donViFieldset->setUseAsBaseFieldset(true);
        $this->add($donViFieldset);

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Tạo đơn vị',
                'class' => 'ui submit button primary',
            ),
        ));
    }
}