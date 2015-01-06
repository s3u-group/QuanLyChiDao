<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

use CongViec\Form\TheoDoiFieldset;


class TheoDoiForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('theo-doi-form');
               
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $fieldset = new TheoDoiFieldset($objectManager);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);

        $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Lưu thông tin'
             ),
         ));
    }
}
