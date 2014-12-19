<?php
namespace User\Form\Element;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Regex as RegexValidator;
use DoctrineModule\Validator\NoObjectExists;

class Phone extends Element implements InputProviderInterface
{
    protected $entityManager;

    protected $validatorObjectExists = false;

    public function setEntityManager($entityManager){
        $this->entityManager = $entityManager;
    }

    public function getEntityManager(){
        return $this->entityManager;
    }

    public function setValidatorObjectExists($validatorObjectExists){
        $this->validatorObjectExists = $validatorObjectExists;
        return $this;
    }

    public function getValidatorObjectExists(){
        return $this->validatorObjectExists;
    }

    public function getInputSpecification()
    {
        if($this->getValidatorObjectExists())
            return array(
                'name' => $this->getName(),
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\Regex',
                        'options' => array(
                            'pattern' => '/^\+?\d{10,11}$/',
                            'messages' => array(
                                RegexValidator::NOT_MATCH => 'Please enter 10 or 11 digits only!'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->getEntityManager()->getRepository('User\Entity\User'),
                            'fields' => 'phoneNumber',
                            'messages' => array(
                                NoObjectExists::ERROR_OBJECT_FOUND => 'Sorry guy, a user with this phone number already exists !'
                            ),
                        )
                    )
                ),
            );
        else
            return array(
                'name' => $this->getName(),
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\Regex',
                        'options' => array(
                            'pattern' => '/^\+?\d{10,11}$/',
                            'messages' => array(
                                RegexValidator::NOT_MATCH => 'Please enter 10 or 11 digits only!'
                            ),
                        ),
                    ),
                ),
            );
    }
}