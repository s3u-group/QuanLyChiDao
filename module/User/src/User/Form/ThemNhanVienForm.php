<?php
namespace User\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class ThemNhanVienForm extends Form
{

    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $sm)
    {
        parent::__construct('them-nhan-vien-form');
        $this->objectManager = $objectManager;

        $this
            ->setAttribute('method', 'post')
            ->setAttribute('autocomplete', 'off')
            ->setHydrator(new DoctrineHydrator($objectManager));        

        /*$this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));*/

        $userFieldset = new UserFieldset($objectManager, $sm);
        $userFieldset->setUseAsBaseFieldset(true);
        $this->add($userFieldset);

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Thêm nhân viên',
                'class' => 'ui blue button',
            ),
        ));
    }

    public function getInputFilter()
    {
        $formInputFilter = parent::getInputFilter();
        $input = $formInputFilter->get('user')->get('dienThoai');
        $myValidator = new \DoctrineModule\Validator\NoObjectExists(array(
            'object_repository' => $this->objectManager->getRepository('User\Entity\User'),
            'fields' => 'dienThoai',
            'messages' => array(
                'objectFound' => 'Điện thoại này đã sử dụng'
            ),
        ));
        $input->getValidatorChain()->addValidator($myValidator);

        $formInputFilter = parent::getInputFilter();
        $input = $formInputFilter->get('user')->get('email');
        $myValidator = new \DoctrineModule\Validator\NoObjectExists(array(
            'object_repository' => $this->objectManager->getRepository('User\Entity\User'),
            'fields' => 'email',
            'messages' => array(
                'objectFound' => 'Email này đã sử dụng'
            ),
        ));
        $input->getValidatorChain()->addValidator($myValidator);

        return $formInputFilter;
    }
}