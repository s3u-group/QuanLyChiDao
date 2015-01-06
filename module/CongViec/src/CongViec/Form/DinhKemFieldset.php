<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use CongViec\Entity\DinhKem;

class DinhKemFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('theo-doi');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new DinhKem());

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
         // File Input
        $dinhKems = new \Zend\Form\Element\File('dinhKems');
        $dinhKems->setAttribute('id', 'dinhKems') 
                 ->setAttribute('label', 'Đính kèm')  
                 ->setAttribute('multiple', true);   // That's it
        $this->add($dinhKems);
        
       
    }
    public function getInputFilterSpecification()
    {
        return array(
            'nguoiKy' => array(
                'required' => false
            )
        );
    }
}
?>