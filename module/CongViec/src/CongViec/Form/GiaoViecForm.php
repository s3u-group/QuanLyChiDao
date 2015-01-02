<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

use CongViec\Form\CongVanFieldset;


class GiaoViecForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('giao-viec');
               
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $fieldset = new CongVanFieldset($objectManager);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));

        $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Lưu thông tin'
             ),
         ));
    }
}
