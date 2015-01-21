<?php 
return array(
	'controllers' => array(
		'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController',
            'User\Controller\DonVi' => 'User\Controller\DonViController',
			'User\Controller\Quyen' => 'User\Controller\QuyenController',
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
                    'paginator' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[trang-:page]',
                            'defaults' => array(
                                'page' => 1,
                            ),
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
                    'paginator' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[trang-:page]',
                            'defaults' => array(
                                'page' => 1,
                            ),
                        ),
                    ),                  
                ) 
            ),
            'quyen' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/quyen',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'Quyen',
                        'action'     => 'danh-sach',
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
                    'controller' => array('Application\Controller\Index'),
                    'action' => array('app-info', 'index'),
                    'roles' => array('nguoi-dung'),
                ),

                //zfcuserController
                array(
                    'controller' => array('zfcuser'),  
                    'action' => array('login'),
                    'roles' => array('khach') 
                ), 

                array(
                    'controller' => array('zfcuser'),  
                    'action' => array('logout', 'changepassword'),
                    'roles' => array('nguoi-dung')
                ),   

                //CongViecController
                array(
                    'controller'=>array('CongViec\Controller\CongViec'),
                    'action'    =>array('index', 'xem-cong-viec', 'chi-tiet-cong-viec', 'ajax-get-to-chuc','xoa-dinh-kem','bao-cao-moi','hoan-thanh'),
                    'roles'     =>array('xu-ly-cong-viec', 'quan-tri'),
                ), 

                array(
                    'controller'=>array('CongViec\Controller\CongViec'),
                    'action'    =>array('giao-viec', 'huy-cong-viec'),
                    'roles'     =>array('giao-viec', 'quan-tri'),
                ),

                // TraCuuController
                array(
                    'controller'=>array('CongViec\Controller\TraCuu'),
                    'action'    =>array('index'),
                    'roles'     =>array('ket-xuat', 'quan-tri'),
                ), 
                
                array(
                    'controller'=>array('CongViec\Controller\TraCuu'),
                    'action'    =>array('cong-viec-don-vi'),
                    'roles'     =>array('nguoi-dung'),
                ),

                // KetXuatController
                array(
                    'controller'=>array('CongViec\Controller\KetXuat'),
                    'action'    =>array('in-danh-sach-cong-viec'),
                    'roles'     =>array('ket-xuat', 'quan-tri'),
                ),

                array(
                    'controller'=>array('CongViec\Controller\KetXuat'),
                    'action'    =>array('in-danh-sach-cong-viec-don-vi', 'in-cong-viec'),
                    'roles'     =>array('nguoi-dung'),
                ),

                // TheoDoiController
                array(
                    'controller'=>array('CongViec\Controller\TheoDoi'),
                    'action'    =>array('index', 'xem-cong-viec'),
                    'roles'     =>array('giao-viec', 'quan-tri'),
                ),

                array(
                    'controller'=>array('CongViec\Controller\TheoDoi'),
                    'action'    =>array('tao-bao-cao', 'bao-cao', 'huy-bao-cao', 'nghiem-thu'),
                    'roles'     =>array('xu-ly-cong-viec', 'quan-tri'),
                ),

                /**xem xet bo phan duoi */
              /*  array(
                    'controller'=>array('CongViec\Controller\TheoDoi'),
                    'action'    =>array('chi-tiet-cong-viec','bao-cao-moi', 'bao-cao-nghiem-thu', 'hoan-thanh', 'huy-bao-cao'),
                    'roles'     =>array('xu-ly-cong-viec'),
                ),*/

                // UserController
                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('them-nhan-vien', 'danh-sach-nhan-vien', 'cap-nhat-nhan-vien', 'cap-tai-khoan', 'phan-quyen','ajax-get-to-chuc','user-roles'),
                    'roles'     =>array('quan-tri'),
                ),

                array(
                    'controller'=>array('User\Controller\Index'),
                    'action'    =>array('ho-so-ca-nhan'),
                    'roles'     =>array('nguoi-dung'),
                ),

                // QuyenController
                array(
                    'controller'=>array('User\Controller\Quyen'),
                    'action'    =>array('tao-quyen', 'danh-sach', 'sua-quyen'),
                    'roles'     =>array('quan-tri'),
                ),

               //DonViController
                array(
                    'controller'=>array('User\Controller\DonVi'),
                    'action'    =>array('tao-moi','cap-nhat', 'danh-muc'),
                    'roles'     =>array('quan-tri'),
                ),
                // TaxonomyController
                array(
                    'controller' => array('Taxonomy\Controller\DanhMuc'),
                    'action' => array('loai-cong-viec', 'sua-loai-cong-viec', 'xoa-loai-cong-viec', 'linh-vuc', 'sua-linh-vuc', 'xoa-linh-vuc'),
                    'roles' => array('quan-tri')
                )
            ),
        ),
    ),
);