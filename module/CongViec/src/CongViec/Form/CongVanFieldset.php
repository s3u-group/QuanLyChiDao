<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use CongViec\Entity\CongVan;
use CongViec\Form\CongViecFieldset;

class CongVanFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('cong-van');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new CongVan());

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'soHieu',
            'type' => 'Text',
            'options'=>array(
                'label' => 'Số hiệu'      
            ),
        ));

        $this->add(array(
            'name' => 'trichYeu',
            'type' => 'Textarea',
            'options'=>array(
                'label' => 'Trích yếu'
            ),
        ));

        $this->add(array(
            'name' => 'ngayBanHanh',
            'type' => 'date',
            'options' => array(
                'label' => 'Ngày ban hành'
            )
        ));

        $this->add(array(
            'name' => 'nguoiKy',
            'type' => 'select',
            'options' => array(
                'label' => 'Người ký'
            )
        ));

        $this->add(array(
            'name' => 'dinhKem',
            'type' => 'file',
            'options' => array(
                'label' => 'Đính kèm'
            )
        ));

        $congViecFieldset = new CongViecFieldset($objectManager);
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'congViecs',
            'options' => array(
                 'label' => 'Danh sách công việc',
                'count' => 1,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => $congViecFieldset
            ),
        ));
       
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