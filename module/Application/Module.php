<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /**
         * Viet hoa
         */
        $translator=$e->getApplication()->getServiceManager()->get('translator');
        $translator->addTranslationFile(
            'phpArray',
            './vendor/zendframework/zendframework/resources/languages/vi/Zend_Validate.php'

        );

        AbstractValidator::setDefaultTranslator($translator);

        /**
         * Tu dong chuyen sang trang login khi chua dang nhap
         */
        $sm = $e->getApplication()->getServiceManager();
        $eventManager->attach(
            'route',
            function($e) {
                $app = $e->getApplication();
                $routeMatch = $e->getRouteMatch();
                $sm = $app->getServiceManager();
                $auth = $sm->get('zfcuser_auth_service');
                if (!$auth->hasIdentity() && $routeMatch->getMatchedRouteName() != 'zfcuser/login') {
                    $response = $e->getResponse();
                    $response->getHeaders()->addHeaderLine(
                        'Location',
                        $e->getRouter()->assemble(
                                array(),
                                array('name' => 'zfcuser/login')
                        )
                    );
                    $response->setStatusCode(302);
                    return $response;
                }
            },
            -100
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
