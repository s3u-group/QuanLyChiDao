<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use User\Entity\User;

class NhanVienFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('nguoiThucHien');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new User());

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
       
    }
    public function getInputFilterSpecification()
    {
        return array(
        );
    }
}
?>