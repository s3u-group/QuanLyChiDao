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
        parent::__construct('cha');

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
                'label' => 'Số hiệu công văn'      
            ),
            'attributes' => array(
                'required' => 'required'
            )
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
            ),
            'attributes' => array(
                'value' => date('Y-m-d'),
                'required' => 'required'
            )
        ));

        /*$this->add(array(
            'name' => 'nguoiKy',
            'type' => 'select',
            'options' => array(
                'label' => 'Người ký',
                'value_options' => $this->getNguoiKyOptions($objectManager)
            ),
            'attributes' => array(
                'class' => 'ui dropdown',
                'required' => 'required'
            )
        ));*/

        $this->add(array(
           'name' => 'nguoiKy',
           'type' => 'DoctrineModule\Form\Element\ObjectSelect',
           'options' => array(
                'object_manager'     => $objectManager,
                'target_class'       => 'User\Entity\User',
                'label' => 'Người ký',
                'value_options' => $this->getNguoiKyOptions($objectManager)
            ),
            'attributes' => array(
                'class' => 'ui dropdown',
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
                'multiple' => true
            )
        ));

        /*$fieldset = new CongViecFieldset($objectManager);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);*/

        /*$congViecFieldset = new CongViecFieldset($objectManager);
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'congViecs',
            'options' => array(
            //    'label' => 'Danh sách công việc',
                'count' => 1,
            //    'should_create_template' => true,
            //    'allow_add' => true,
                'target_element' => $congViecFieldset
            ),
        ));*/
       
    }
    public function getInputFilterSpecification()
    {
        return array(
            'soHieu' => array(
                'required' => true
            ),
            'ngayBanHanh' => array(
                'required' => true
            ),
            'nguoiKy' => array(
                'required' => true
            )
        );
    }

    public function getNguoiKyOptions($objectManager){
        $options = array();
        $query = $objectManager->createQuery('select u from User\Entity\User u');
        $users = $query->getResult();
        foreach($users as $user){
            $options[$user->getId()] = $user->getHoTen();
        }
        return $options;
    }
}
?>