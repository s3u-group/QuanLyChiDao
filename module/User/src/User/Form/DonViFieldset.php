<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use User\Entity\DonVi;

class DonViFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager, $id = null)
    {
        $this->objectManager = $objectManager;
        parent::__construct('don-vi');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new DonVi())
        ;

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'tenDonVi',
            'type' => 'text',
            'options' => array(
                'label' => 'Tên đơn vị',
            ),
        ));

        $this->add(array(
            'name' => 'tenVietTat',
            'type' => 'text',
            'options' => array(
                'label' => 'Tên viết tắt'
            )
        )); 

        /**
         * Choi chieu de tranh loi khi don vi chua co nhan vien
         */
        if($options = $this->getUserOptions($objectManager, $id)){
            $this->add(array(
               'name' => 'thuTruong',
               'type' => 'DoctrineModule\Form\Element\ObjectSelect',
               'options' => array(
                    'object_manager'     => $objectManager,
                    'target_class'       => 'User\Entity\User',
                //    'property' => 'thuTruong',
                    'label' => 'Thủ trưởng',
                    'value_options' => $options,
                ),
                'attributes' => array(
                    'class' => 'ui dropdown'
                )
            ));   
        }
        else{
            $this->add(array(
                'type' => 'hidden',
                'name' => 'thuTruong'
            ));
        }            
    }

    public function getInputFilterSpecification()
    {
        return array( 
            'tenDonVi' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Tên đơn vị không được rỗng' 
                            ),
                        ),
                    ),
                    /*array( //da chuyen sang form
                        'name' => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->objectManager->getRepository('User\Entity\DonVi'),
                            'fields' => 'tenDonVi',
                            'messages' => array(
                                'objectFound' => 'Tên đơn vị đã tồn tại !'
                            ),
                        )
                    )*/
                )
            ),
            'tenVietTat' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Tên viết tắt không được rỗng' 
                            ),
                        ),
                    ),
                    /*array(
                        'name' => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->objectManager->getRepository('User\Entity\DonVi'),
                            'fields' => 'tenVietTat',
                            'messages' => array(
                                'objectFound' => 'Tên đơn vị hoặc tên viết tắt đã tồn tại !'
                            ),
                        )
                    )*/
                )
            ),
        );
    }

    public function getUserOptions($objectManager, $id){
        $options = array();
        $query = $objectManager->createQuery('select u from User\Entity\User u where u.donVi = ?1');
        $query->setParameter(1, $id);
        $users = $query->getResult();
        foreach($users as $user){
            $options[$user->getId()] = $user->getHoTen();
        }
        return $options;        
    }
}