<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class CreateDonViForm extends Form
{
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        parent::__construct('create-don-vi-form');

        $this
            ->setAttribute('method', 'post')
            ->setAttribute('autocomplete', 'off')
            ->setHydrator(new DoctrineHydrator($objectManager))
            ;        

        /*$this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));*/

        $donViFieldset = new DonViFieldset($objectManager);
        $donViFieldset->setUseAsBaseFieldset(true);
        $this->add($donViFieldset);

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Tạo đơn vị',
                'class' => 'ui blue button',
            ),
        ));
    }

    public function getInputFilter()
    {
        $formInputFilter = parent::getInputFilter();
        $tenDonViInput = $formInputFilter->get('don-vi')->get('tenDonVi');
        $myValidator = new \DoctrineModule\Validator\NoObjectExists(array(
            'object_repository' => $this->objectManager->getRepository('User\Entity\DonVi'),
            'fields' => 'tenDonVi',
            'messages' => array(
                'objectFound' => 'Tên đơn vị đã tồn tại !'
            ),
        ));
        $tenDonViInput->getValidatorChain()->addValidator($myValidator);

        $tenVietTatInput = $formInputFilter->get('don-vi')->get('tenVietTat');
        $myValidator = new \DoctrineModule\Validator\NoObjectExists(array(
            'object_repository' => $this->objectManager->getRepository('User\Entity\DonVi'),
            'fields' => 'tenVietTat',
            'messages' => array(
                'objectFound' => 'Tên đơn vị hoặc tên viết tắt đã tồn tại !'
            ),
        ));
        $tenVietTatInput->getValidatorChain()->addValidator($myValidator);

        return $formInputFilter;
    }
}