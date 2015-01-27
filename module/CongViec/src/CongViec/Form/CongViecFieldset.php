<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use CongViec\Entity\CongViec;
use CongViec\Form\CongVanFieldset;
use CongViec\Form\PhanCongFieldset;

class CongViecFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager, $sm = null)
    {
        parent::__construct('congViec');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new CongViec());

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
           'name' => 'loai',
           'type' => 'DoctrineModule\Form\Element\ObjectSelect',
           'options' => array(
                'object_manager'     => $objectManager,
                'target_class'       => 'Taxonomy\Entity\TermTaxonomy',
                'label' => 'Loại luồng',
                'value_options' => $this->getLoaiOption($sm),
                'empty_option' => ''
            ),
            'attributes' => array(
                'class' => 'ui dropdown',
                'id' => 'loai-cong-viec',
                'required' => 'required'
            )
        ));

        $this->add(array(
           'name' => 'linhVuc',
           'type' => 'DoctrineModule\Form\Element\ObjectSelect',
           'options' => array(
                'object_manager'     => $objectManager,
                'target_class'       => 'Taxonomy\Entity\TermTaxonomy',
                'label' => 'Lĩnh vực',
                'value_options' => $this->getLinhVucOption($sm),
                'empty_option' => ''
            ),
            'attributes' => array(
                'class' => 'ui dropdown',
                'required' => 'required'
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
        	),
            'attributes' => array(
                'required' => 'required'
            )
        ));

        $this->add(array(
        	'name' => 'ngayBanHanh',
        	'type' => 'date',
        	'options' => array(
        		'label' => 'Ngày bắt đầu'
        	),
            'attributes' => array(
                'value' => date('Y-m-d'),
                'required' => 'required'
            )
        ));

        $this->add(array(
        	'name' => 'ngayHoanThanh',
        	'type' => 'date',
        	'options' => array(
        		'label' => 'Ngày hoàn thành'
        	),
            'attributes' => array(
                'required' => 'required'
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

        $fieldsetCongVan = new CongVanFieldset($objectManager);
        $fieldsetCongVan->setUseAsBaseFieldset(true);
        $this->add($fieldsetCongVan);

        $this->add(array(
            'type' => 'hidden',
            'name' => 'nguoiTao'
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
                'required' => true
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

    public function getLinhVucOption($sm){
        if($sm){
            $taxonomyService = $sm->get('taxonomyService');
            $options = $taxonomyService->getValueForOption('linh-vuc');
            return $options;
        }
    }
}