<?php
namespace Taxonomy\Form;

use Taxonomy\Form\TermTaxonomyFieldset;
use Taxonomy\Form\TermTaxonomyFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class UpdateTaxonomyForm extends Form
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('update-taxonomy-form');

        $this
            ->setAttribute('method', 'post')
            ->setHydrator(new DoctrineHydrator($objectManager))
        ;

        $termTaxonomyFieldset = new TermTaxonomyFieldset($objectManager);
        $termTaxonomyFieldset->setUseAsBaseFieldset(true);
        $this->add($termTaxonomyFieldset);

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'class' => 'ui blue button',
            ),
        ));
    }
}