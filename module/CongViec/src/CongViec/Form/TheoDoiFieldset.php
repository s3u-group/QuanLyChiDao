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
            'name' => 'ngayBaoCao',
            'type' => 'date',
            'options'=>array(
                'label' => 'Ngày báo cáo'      
            ),
        ));

        $this->add(array(
            'name' => 'noiDung',
            'type' => 'Textarea',
            'options'=>array(
                'label' => 'nội dung'
            ),
        ));

        $this->add(array(
            'name' => 'congVan',
            'type' => 'Hidden',
        ));
         $this->add(array(
             'name' => 'nguoiBaoCao',
             'type' => 'Hidden',
             'options' => array(
                 'label' => 'Người báo cáo mặc định là người chủ trì',
             ),
         ));
         $this->add(array(
             'name' => 'nguoiTao',
             'type' => 'Hidden',
         ));

        // File Input
        $dinhKems = new \Zend\Form\Element\File('dinhKems');
        $dinhKems->setAttribute('id', 'dinhKems') 
                 ->setAttribute('label', 'Đính kèm')  
                 ->setAttribute('multiple', true);   // That's it
        $this->add($dinhKems);
       
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
            'nguoiBaoCao' => array(
                'required' => false // tam thoi de false
            ),
            'ngayBaoCao' => array(
                'required' => true
            ),
        );
    }
}
?>