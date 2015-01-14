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

    public function __construct(ObjectManager $objectManager)
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

}