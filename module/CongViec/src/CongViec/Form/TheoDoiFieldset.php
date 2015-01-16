<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use CongViec\Entity\TheoDoi;

class TheoDoiFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('theo-doi');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new TheoDoi());

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'type' => 'hidden',
            'name' => 'nguoiTao'
        ));

        $this->add(array(
            'type' => 'hidden',
            'name' => 'congVan'
        ));

        $this->add(array(
            'name' => 'noiDung',
            'type' => 'Textarea',
            'options'=>array(
                'label' => 'Nội dung'
            ),
        ));

        $this->add(array(
            'name' => 'dinhKems',
            'type' => 'file',
            'options' => array(
                'label' => 'Đính kèm'
            ),
            'attributes' => array(
                'id' => 'dinhKem',
                'multiple' => true
            )
        ));
       
    }
    public function getInputFilterSpecification()
    {
        return array(
            'dinhKems' => array(
                'required' => false
            ),
            'noiDung' => array(
                'required' => true
            ),
        );
    }
}
?>