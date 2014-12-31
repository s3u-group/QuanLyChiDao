<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use CongViec\Form\TaoCongVanFieldset;


class TaoCongVanForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('tao-cong-van');
               
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $phieuThuFieldset = new TaoCongVanFieldset($objectManager);
        $phieuThuFieldset->setUseAsBaseFieldset(true);
        $this->add($phieuThuFieldset);


        $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
    }
}
