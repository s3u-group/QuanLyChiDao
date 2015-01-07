<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            'main' => 'Application\Navigation\Service\MainNavigationFactory'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/blank'           => __DIR__ . '/../view/layout/blank.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'menu/default'            => __DIR__ . '/../view/partial/menu/default.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy', //add to use AJAX
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),

    'navigation' => array(
        'main' => array(
            array(
                'label' => 'Quản lý công việc',
                'uri' => '#',
                'icon' => '<i class="feed icon"></i>',
                'pages' => array(
                    array(
                        'label' => 'Công việc cần xử lý',
                        'route' => 'cong_viec'
                    ),
                    array(
                        'label' => 'Giao việc mới',
                        'route' => 'cong_viec/crud',
                        'params' => array(
                            'action' => 'giao-viec'
                        )
                    ),
                    array(
                        'label' => 'Theo dõi việc đã giao',
                        'route' => 'theo_doi/crud',
                        'params' => array(
                            'action' => 'index'
                        )
                    ),
                    array(
                        'label' => 'Báo cáo nghiệm thu',
                        'route' => 'theo_doi/crud',
                        'params' => array(
                            'action' => 'bao-cao-nghiem-thu'
                        )
                    ),
                    array(
                        'label' => 'Nhật ký công việc',
                        //'uri' => '#'
                        'route' => 'cong_viec/crud',
                        'params' => array(
                            'action' => 'nhat-ky-cong-viec',
                        )
                    ),
                ),
            ),
            array(
                'label' => 'Quản lý tổ chức',
                'uri' => '#',
                'icon' => '<i class="sitemap icon"></i>',
                'pages' => array(
                    array(
                        'label' => 'Tạo tài khoản',
                        /*'uri' => '#'*/
                        'route' => 'user/crud',
                        'params' => array(
                            'action' => 'createAccount',
                        )
                    ),
                    array(
                        'label' => 'Danh sách nhân viên',
                        /*'uri' => '#'*/
                        'route' => 'user/crud',
                        'params' => array(
                            'action' => 'list',
                        )
                    ),
                    array(
                        'label' => 'Danh mục đơn vị',
                        //'uri' => '#'
                        'route' => 'user/crud',
                        'params' => array(
                            'action' => 'danh-muc-don-vi',
                        )
                    ),
                    array(
                        'label' => 'Phân quyền',
                        'uri' => '#'
                    )
                ),
            ),
            array(
                'label' => 'Hệ thống',
                'uri' => '#',
                'icon' => '<i class="setting icon"></i>',
                'pages' => array(
                    array(
                        'label' => 'Hồ sơ cá nhân',
                        'route' => 'user/crud',
                        'params' => array(
                            'action' => 'view'
                        )
                    ),
                    array(
                        'label' => 'Đổi mật khẩu',
                        'route' => 'zfcuser/changepassword'
                    ),
                    array(
                        'label' => 'Thông tin phần mềm',
                        'route' => 'application/default',
                        'params' => array(
                            'controller' => 'index',
                            'action' => 'app-info'
                        )
                    )
                ),
            ),
        ),
    ),
);
