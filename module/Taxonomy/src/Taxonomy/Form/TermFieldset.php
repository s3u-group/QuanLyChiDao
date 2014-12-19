<?php
namespace Taxonomy\Form;

use Taxonomy\Entity\Term;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class TermFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        parent::__construct('term'); //phai dat trung voi khoa ngoai

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new Term())
        ;

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name',
            )
        ));

        $this->add(array(
            'name' => 'slug',
            'type' => 'text',
            'options' => array(
                'label' => 'SLug',
            ),
            'attributes' => array(
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true
            ),
            'slug' => array(
                'required' => false,
            /*    'validators' => array(
                    array(
                        'name' => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->objectManager->getRepository('Taxonomy\Entity\Term'),
                            'fields' => 'slug',
                            'messages' => array(
                                'objectFound' => 'Sorry guy, a term with this slug already exists !'
                            ),
                        )
                    )
                )*/
            )
        );
    }

}