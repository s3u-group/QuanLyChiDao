<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use User\Entity\DonVi;

class DonViFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        parent::__construct('don-vi');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new DonVi())
        ;

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'tenDonVi',
            'type' => 'text',
            'options' => array(
                'label' => 'Tên đơn vị',
            ),
            'attributes' => array(
                'placeholder' => 'Nhập tên đơn vị'
            ),
        ));
        $this->add(array(
            'name' => 'nhanViens',
            'type' => 'hidden'
        ));
        $this->add(array(
            'name' => 'congVanDens',
            'type' => 'hidden'
        ));        
    }

    public function getInputFilterSpecification()
    {
        return array(            
        );
    }

}