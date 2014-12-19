<?php
namespace Taxonomy\Form;

use Taxonomy\Entity\TermTaxonomy;
use Taxonomy\Form\TermFieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class TermTaxonomyFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('taxonomy');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new TermTaxonomy())
        ;

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

        $termFieldset = new TermFieldset($objectManager);
        $termFieldset->setUseAsBaseFieldset(true);
        $this->add($termFieldset);

        $this->add(array(
            'name' => 'description',
            'type' => 'text',
            'options' => array(
                'label' => 'Description'
            )
        ));

        $this->add(array(
            'name' => 'parent',
            'type' => 'select',
            'options' => array(
                'label' => 'Parent',
                'empty_option' => '-- Parent --',
                'disable_inarray_validator' => true
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'parent' => array(
                'required' => false
            )
        );
    }

}