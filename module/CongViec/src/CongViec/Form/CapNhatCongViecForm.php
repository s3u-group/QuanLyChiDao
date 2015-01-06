<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

use CongViec\Form\CongViecFieldset;

class CapNhatCongViecForm extends Form
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('cap-nhat-cong-viec-form');

        $this->setAttribute('method', 'post')
             ->setHydrator(new DoctrineHydrator($objectManager));

        $congViecFieldset = new CongViecFieldset($objectManager);
        $congViecFieldset->setUseAsBaseFieldset(true);
        $this->add($congViecFieldset);


        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Lưu',
                'class' => 'tiny ui button item blue',
            ),
        ));
    }
}