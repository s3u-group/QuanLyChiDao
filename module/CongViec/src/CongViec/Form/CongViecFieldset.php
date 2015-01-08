<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use CongViec\Entity\CongViec;
use CongViec\Form\PhanCongFieldset;

class CongViecFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager, $sm = null)
    {
        parent::__construct('congViecs');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new CongViec());

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
        	'name' => 'loai',
        	'type' => 'select',
        	'options' => array(
        		'label' => 'Loại luồng',
                'value_options' => $this->getLoaiOption($sm)
        	),
            'attributes' => array(
                'class' => 'ui dropdown'
            )
        ));

        $this->add(array(
            'name' => 'ten',
            'type' => 'text',
            'options' => array(
                'label' => 'Chủ đề'
            )
        ));

        $this->add(array(
        	'name' => 'noiDung',
        	'type' => 'textarea',
        	'options' => array(
        		'label' => 'Nội dung'
        	)
        ));

        $this->add(array(
        	'name' => 'ngayBanHanh',
        	'type' => 'date',
        	'options' => array(
        		'label' => 'Ngày bắt đầu'
        	),
            'attributes' => array(
                'value' => date('Y-m-d')
            )
        ));

        $this->add(array(
        	'name' => 'ngayHoanThanh',
        	'type' => 'date',
        	'options' => array(
        		'label' => 'Ngày hoàn thành'
        	)
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

        $phanCongFieldset = new PhanCongFieldset($objectManager);
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'nguoiThucHiens',
            'options' => array(
            //    'label' => 'Danh sách công việc',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => $phanCongFieldset
            ),
            'attributes' => array(
                'class' => 'ui hidden'
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'loai' => array(
                'required' => false
            ),
            'ten' => array(
                'required' => false
            ),
            'noiDung' => array(
                'required' => true
            ),
            'ngayBanHanh' => array(
                'required' => true
            ),
            'ngayHoanThanh' => array(
                'required' => false
            )
        );
    }

    public function getLoaiOption($sm){
        if($sm){
            $taxonomyService = $sm->get('taxonomyService');
            $options = $taxonomyService->getValueForOption('loai-cong-viec');
            return $options;
        }
    }
}