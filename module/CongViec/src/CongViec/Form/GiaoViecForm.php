<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

use CongViec\Form\CongViecFieldset;


class GiaoViecForm extends Form
{
    public function __construct(ObjectManager $objectManager, $sm = null)
    {
        parent::__construct('giao-viec');
               
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $fieldset = new CongViecFieldset($objectManager, $sm);
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
                'value' => 'Gửi công việc đi',
                'class' => 'ui blue submit button'
             ),
         ));
    }
}
