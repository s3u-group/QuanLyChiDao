<?php
namespace Taxonomy;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig(){
        return array(
            'factories' => array(
                'taxonomy_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['taxonomy']) ? $config['taxonomy'] : array());
                },
            )
        );
    }

    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'taxonomyPlugin' => function($sm){
                    $taxonomyPlugin = new \Taxonomy\Controller\Plugin\TaxonomyPlugin();
                    $om = $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    $taxonomyPlugin->setObjectManager($om);
                    return $taxonomyPlugin;
                }
            ),
        );
    }
}
?>