<?php 
return array(
	'controllers' => array(
		'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController',
			'User\Controller\DonVi' => 'User\Controller\DonViController',
		)
	),

    'controller_plugins' => array(
        'factories'=>array(
            'kiemTraQuyenCuaUser' => function($sm){
                $entityManager=$sm->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $serviceManager=$sm->getServiceLocator();
                $kiemTraQuyenCuaUser=new User\Controller\Plugin\KiemTraQuyenCuaUser();
                $kiemTraQuyenCuaUser->setEntityManager($entityManager);
                $kiemTraQuyenCuaUser->setServiceManager($serviceManager);
                return $kiemTraQuyenCuaUser;
            },
        ),
    ),

	'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
            'zfcuser' => __DIR__ . '/../view',
        ),        
    ),

    'view_helpers'=>array(
        'invokables'=>array(
            'make_array_option_don_vi'=>'User\View\Helper\MakeArrayOptionDonVi',
        ),
    ),

    'service_manager' => array(
        'invokables' => array(
            'user_service' => 'User\Service\User'
        ),
        'factories' => array(
            'user_module_options' => function ($sm) {
                $config = $sm->get('Config');
                return new \User\Options\ModuleOptions(isset($config['user']) ? $config['user'] : array());
            },
        ),
    ),

	'router' => array(
		'routes' => array(
			'user' => array(
				'type' => 'literal',
				'options' => array(
					'route' => '/nguoi-dung',
					'defaults' => array(
						'__NAMESPACE__' => 'User\Controller',
						'controller' => 'Index',
                        'action'     => 'list',
					)
				),
				'may_terminate' => true,
                'child_routes' => array(
                	'crud' => array(
                		'type'    => 'segment',
                        'options' => array(
                            'route'    => '[/][:action][/:id]',
                            'constraints' => array(                            
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*'
                            ),  
                           /* 'defaults' => array(
                                'action'     => 'list',
                            )  */                        
                        ),
                	),                   
                )
			),
            'don_vi' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/don-vi',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'DonVi',
                        'action'     => 'danh-muc',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'crud' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '[/][:action][/:id]',
                            'constraints' => array(                            
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*'
                            ),  
                           /* 'defaults' => array(
                                'action'     => 'list',
                            )  */                        
                        ),
                    ),                   
                ) 
            ),
		),
	),

	'doctrine' => array(
        'driver' => array(
            'user_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__.'/../src/User/Entity',
                ),
            ),

            'orm_default' => array(
                'drivers' => array(
                    'User\Entity' => 'user_annotation_driver'
                )
            )
        )
    ),

    //phân quyền theo zfcuser
    
     'bjyauthorize'=>array(
 
        'guards'=>array(
            'BjyAuthorize\Guard\Controller'=>array(                
                // ApplicationController
                array(
                    'controller'=>array('Application\Controller\Index'),
                    'action'    =>array('app-info'),
                    'roles'     =>array('ho-so-ca-nhan','admin'),
                ),

                //zfcuserController
                array(
                    'controller'=>array('zfcuser'),  
                    'action'    =>array('login'),                 
                    'roles'     =>array('khach'),
                ),

                array(
                    'controller'=>array('zfcuser'),  
                    'action'    =>array('logout'),                 
                    'roles'     =>array('cong-viec-can-xu-ly','giao-viec-moi','theo-doi-viec-da-giao','bao-cao-nghiem-thu','nhat-ky-cong-viec','tao-tai-khoan','danh-sach-nhan-vien', 'tao-don-vi','danh-muc-don-vi','phan-quyen','ho-so-ca-nhan','doi-mat-khau','thong-tin-phan-mem','admin'),
                ),   

                array(
                    'controller'=>array('zfcuser'),
                    'action'    =>array('changepassword'),
                    'roles'     =>array('doi-mat-khau','admin'),
                ),            

                /**
                 * @var chưa sử dụng
                 */
                //CongVanController
                array(
                    'controller'=>array('CongViec\Controller\CongVan'),
                    'action'    =>array('index','cong-van-moi'),
                    'roles'     =>array('admin'),
                ),

                //CongViecController
                array(
                    'controller'=>array('CongViec\Controller\CongViec'),
                    'action'    =>array('index','chi-tiet-cong-viec'),
                    'roles'     =>array('cong-viec-can-xu-ly'),
                ),

                array(
                    'controller'=>array('CongViec\Controller\CongViec'),
                    'action'    =>array('giao-viec','ajax-get-to-chuc','chi-tiet-cong-viec','xoa-dinh-kem','bao-cao-moi','hoan-thanh'),
                    'roles'     =>array('giao-viec-moi'),
                ),

                array(
                    'controller'=>array('CongViec\Controller\CongViec'),
                    'action'    =>array('nhat-ky-cong-viec','chi-tiet-cong-viec','xuat-bao-cao'),
                    'roles'     =>array('nhat-ky-cong-viec'),
                ),               

                /**
                 * @var chưa sử dụng
                 */
                // PhanCongController
                array(
                    'controller'=>array('CongViec\Controller\PhanCong'),
                    'action'    =>array(),
                    'roles'     =>array('admin'),
                ),
                

                // TheoDoiController
                array(
                    'controller'=>array('CongViec\Controller\TheoDoi'),
                    'action'    =>array('index','chi-tiet-cong-viec','bao-cao-moi','hoan-thanh'),
                    'roles'     =>array('theo-doi-viec-da-giao','admin'),
                ),

                array(
                    'controller'=>array('CongViec\Controller\TheoDoi'),
                    'action'    =>array('bao-cao-nghiem-thu','bao-cao-moi','chi-tiet-cong-viec','hoan-thanh'),
                    'roles'     =>array('bao-cao-nghiem-thu','admin'),
                ),

                array(
                    'controller'=>array('CongViec\Controller\TheoDoi'),
                    'action'    =>array('huy-bao-cao'),
                    'roles'     =>array('giao-viec-moi','theo-doi-viec-da-giao','bao-cao-nghiem-thu','admin'),
                ),


                // UserController
                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('them-nhan-vien', 'cap-nhat-nhan-vien', 'cap-tai-khoan'),
                    'roles'     =>array('tao-tai-khoan','admin'),
                ),
                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('create-account','update'),
                    'roles'     =>array('tao-tai-khoan','admin'),
                ),
                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('list', 'danh-sach-nhan-vien'),
                    'roles'     =>array('danh-sach-nhan-vien','admin'),
                ),
                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('tao-don-vi','sua-don-vi'),
                    'roles'     =>array('tao-don-vi','admin'),
                ),
                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('danh-muc-don-vi'),
                    'roles'     =>array('danh-muc-don-vi','admin'),
                ),
                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('phan-quyen','ajax-get-to-chuc','user-roles'),
                    'roles'     =>array('phan-quyen','admin'),
                ),
                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('view'),
                    'roles'     =>array('ho-so-ca-nhan','admin'),
                ),

                //DonViController
                array(
                    'controller'=>array('User\Controller\DonVi'),
                    'action'    =>array('tao-moi','cap-nhat'),
                    'roles'     =>array('tao-don-vi','admin'),
                ),
                array(
                    'controller'=>array('User\Controller\DonVi'),
                    'action'    =>array('danh-muc'),
                    'roles'     =>array('danh-muc-don-vi','admin'),
                ),

                /**
                 * @var admin có toàn quyền trên hệ thống
                 */

            ),
        ),
    ),
);