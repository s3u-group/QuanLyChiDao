<?php
namespace CongViec\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Element;
use Zend\Form\Form;
use CongViec\Entity\CongVan;


class TaoCongVanFieldset extends Fieldset implements InputFilterProviderInterface
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
             'name' => 'ten',
             'type' => 'Text',
             'attributes'=>array(
                'id'=>'ten',                
            ),
        ));

        $this->add(array(
             'name' => 'loai',
             'type' => '\Zend\Form\Element\Select',
             'options' => array(
                 'label' => 'Loại',
                 'empty_option'=>'Chọn loại công văn',
                 'disable_inarray_validator' => true,
             ),
             'attributes'=>array(
                //'required'=>'required',
                'class' =>'four wide column',
             ),
         ));

        $this->add(array(
             'name' => 'trichYeu',
             'type' => 'Text',
             'attributes'=>array(
                'id'=>'trichYeu',                
            ),
        ));

         $this->add(array(
             'name' => 'noiDung',
             'type' => 'Textarea',
             'attributes'=>array(
                'id'=>'noiDung',                
            ),
        ));

        $this->add(array(
             'name' => 'ngayBanHanh',
             'type' => 'Date',
             'options' => array(                 
             ),
             'attributes'=>array(
             	'id'=>'ngayBanHanh',                
            ),
         ));        

        $this->add(array(
             'name' => 'ngayHoanThanh',
             'type' => 'Date',             
             'attributes'=>array(                
                'id'=>'ngayHoanThanh',
            ),
        ));
        /*$this->add(array(
             'name' => 'ngayHoanThanhThuc',
             'type' => 'Date',             
             'attributes'=>array(                
                'id'=>'ngayHoanThanhThuc',
                'Hidden'=>true,
            ),
        ));*/
        $this->add(array(
             'name' => 'ngayTao',
             'type' => 'Date',             
             'attributes'=>array(                
                'id'=>'ngayTao',
                'Hidden'=>true,
            ),
        ));

         $this->add(array(
             'name' => 'nguoiTao',
             'type' => 'Hidden',
        ));
         $this->add(array(
             'name' => 'trangThai',
             'type' => 'Hidden',
        ));

         $this->add(array(
             'name' => 'dinhKem',
             'type' => 'file',
        ));
       
    }
    public function getInputFilterSpecification()
    {
        return array(
          
        );
    }
}
?>