<?php
namespace CongViec;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            /*'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),*/
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        // khúc này phải có

        $services = $e->getApplication()->getServiceManager();       
        $zfcServiceEvents = $services->get('zfcuser_user_service')->getEventManager();


        // Store the field
        $zfcServiceEvents->attach('register', function($e) use($services) {
            $user=$e->getParam('user');//lấy người dùng hiện tại đang đăng ký ở event
            $em=$services->get('Doctrine\ORM\EntityManager');// lệnh kết nôi doctrine orm
            $defaultUserRole=$em->getRepository('User\Entity\Role')// kết nối tới file Role trong danh mục
                                ->findOneBy(array('roleId'=>'nguoi-dung'));// lấy lấy 1 dòng có roleId có tên là người dùng
            $user->addRole($defaultUserRole);//
            
        });
    }

}
?>