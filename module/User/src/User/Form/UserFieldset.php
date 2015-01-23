<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use User\Entity\User;

class UserFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager, $sm = null)
    {
        $this->objectManager = $objectManager;
        parent::__construct('user');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new User())
        ;

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
               // 'placeholder' => 'Nhập email'
            ),
        ));

        $this->add(array(
            'name' => 'ho',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Họ'
            ),
            'options' => array(
                'label' => 'Họ'
            )
        ));

        $this->add(array(
            'name' => 'ten',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Tên'
            ),
            'options' => array(
                'label' => 'Tên'
            )
        ));

        $this->add(array(
            'name' => 'gioiTinh',
            'type' => 'select',
            'attributes' => array(
                'class' => 'ui dropdown'
            ),
            'options' => array(
                'label' => 'Giới tính',
                'value_options'=>array(
                    '1'=>'Nam',
                    '2'=>'Nữ'
                ),                
            )
        ));

        $this->add(array(
            'name' => 'diaChi',
            'type' => 'text',
            'options' => array(
                'label' => 'Địa chỉ',
            ),
            'attributes' => array(
              //  'placeholder' => 'Nhập địa chỉ'
            ),
        ));      
        
        $this->add(array(
             'name' => 'donVi',
             'type' => 'select',
             'options' => array(
                 'label' => 'Đơn vị',
                 'value_options' => $this->getDonViOptions($objectManager)
             ),
             'attributes'=>array(
                'class'  => 'ui dropdown'           
             ),
         ));   

         $this->add(array(
            'name' => 'chucVu',
            'type' => 'select',
            'options' => array(
                'label' => 'Chức vụ',
                'value_options' => $this->getChucVuOption($sm),
            ),
            'attributes' => array(
                'class' => 'ui dropdown'
            )
        ));     

        $dienThoai = new \User\Form\Element\Phone();
        $dienThoai->setEntityManager($objectManager);
        $dienThoai->setName('dienThoai');
        $dienThoai->setOptions(array(
            'label' => 'Điện thoại'
        ));
        $this->add($dienThoai);   
    }

    public function getInputFilterSpecification()
    {
        return array(
            'ho' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Họ không được rỗng' 
                            ),
                        ),
                    ),
                ),
            ),
            'ten' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Tên không được rỗng' 
                            ),
                        ),
                    ),
                ),
            ),
            'dienThoai' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Điện thoại không được rỗng' 
                            ),
                        ),
                    ),
                ),
            ),
            'diaChi' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Địa không được rỗng' 
                            ),
                        ),
                    ),
                ),
            ),
            'email' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Email không được rỗng' 
                            ),
                        ),
                    ),
                ),
            ),
        );
    }

    public function getDonViOptions($objectManager){
        $options = array();
        $query = $objectManager->createQuery('select dv from User\Entity\DonVi dv');
        $donVis = $query->getResult();
        foreach($donVis as $donVi){
            $options[$donVi->getId()] = $donVi->getTenDonVi();
        }
        return $options;        
    }

    public function getChucVuOption($sm){
        if($sm){
            $taxonomyService = $sm->get('taxonomyService');
            $options = $taxonomyService->getValueForOption('chuc-vu');
            return $options;
        }
    }

}