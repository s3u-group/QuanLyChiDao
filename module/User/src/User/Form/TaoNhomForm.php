<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class TaoNhomForm extends Form
{

    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('tao-nhom-form');
        $this->objectManager = $objectManager;

        $this
            ->setAttribute('method', 'post')
            ->setAttribute('autocomplete', 'off')
            ->setHydrator(new DoctrineHydrator($objectManager));        

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));

        $nhomFieldset = new NhomFieldset($objectManager);
        $nhomFieldset->setUseAsBaseFieldset(true);
        $this->add($nhomFieldset);

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Lưu thông tin',
                'class' => 'ui blue button right floated',
            ),
        ));
    }
}