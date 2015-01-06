<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use CongViec\Entity\CongViec;


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
            'name' => 'dinhKem',
            'type' => 'file',
            'options' => array(
                'label' => 'Đính kèm',
                'multiple'=>true,
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'loai' => array(
                'required' => true
            ),
            'ten' => array(
                'required' => true
            ),
            'noiDung' => array(
                'required' => true
            )
        );
    }

    public function getLoaiOption($sm){
        $taxonomyService = $sm->get('taxonomyService');
        $options = $taxonomyService->getValueForOption('loai-cong-viec');
        return $options;
    }
}