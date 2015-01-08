<?php
namespace CongViec\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use CongViec\Entity\PhanCong;
use CongViec\Form\NhanVienFieldset;

class PhanCongFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('nguoiThucHiens');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new PhanCong());

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $fieldset = new NhanVienFieldset($objectManager);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);

        $this->add(array(
            'name' => 'vaiTro',
            'type' => 'Hidden',
            'attributes' => array(

            )
        ));
       
    }
    public function getInputFilterSpecification()
    {
        return array(
        );
    }
}
?>