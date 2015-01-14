<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

use User\Form\DonViFieldset;

class UpdateDonViForm extends Form
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('update-don-vi-form');

        $this
            ->setAttribute('method', 'post')
            ->setHydrator(new DoctrineHydrator($objectManager))
        ;

        $donViFieldset = new DonViFieldset($objectManager);
        $donViFieldset->setUseAsBaseFieldset(true);
        $this->add($donViFieldset);

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