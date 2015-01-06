<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use CongViec\Entity\CongViec;


class CongViecFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
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
        		'label' => 'Loại luồng'
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

        // File Input
        $dinhKems = new \Zend\Form\Element\File('dinhKem');
        $dinhKems->setAttribute('id', 'dinhKem') 
                 ->setAttribute('label', 'Đính kèm')  
                 ->setAttribute('multiple', true);   // That's it
        $this->add($dinhKems);
    }

    public function getInputFilterSpecification()
    {
        return array(
            'loai' => array(
                'required' => false
            )
        );
    }
}