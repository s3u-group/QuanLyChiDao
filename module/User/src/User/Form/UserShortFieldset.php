<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use User\Entity\User;

class UserShortFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        parent::__construct('user');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new User())
        ;

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));
    }

    public function getInputFilterSpecification(){
        return array();
    }
}