<?php 
return array(
	'controllers' => array(
		'invokables' => array(
			'User\Controller\Index' => 'User\Controller\IndexController'
		)
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
			)
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
    
    /* 'bjyauthorize'=>array(
        'guards'=>array(
            'BjyAuthorize\Guard\Controller'=>array(                
                
                array(
                    'controller'=>array('zfcuser'),  
                    'action'    =>array('login'),                 
                    'roles'     =>array('khach'),
                ),

                array(
                    'controller'=>array('zfcuser'),  
                    'action'    =>array('logout'),                 
                    'roles'     =>array('cong-viec-can-xu-ly','giao-viec-moi','theo-doi-viec-da-giao','bao-cao-nghiem-thu','nhat-ky-cong-viec','tao-tai-khoan','danh-sach-nhan-vien', 'tao-don-vi','danh-muc-don-vi','phan-quyen','ho-so-ca-nhan','doi-mat-khau','thong-tin-phan-mem'),
                ),                

                array(
                    'controller'=>array('CongViec\Controller\CongVan'),
                    'action'    =>array(),
                    'roles'     =>array(),
                ),
                array(
                    'controller'=>array('CongViec\Controller\CongViec'),
                    'action'    =>array('index'),
                    'roles'     =>array('cong-viec-can-xu-ly'),
                ),
                array(
                    'controller'=>array('CongViec\Controller\PhanCong'),
                    'action'    =>array(),
                    'roles'     =>array(),
                ),
                array(
                    'controller'=>array('CongViec\Controller\TheoDoi'),
                    'action'    =>array(),
                    'roles'     =>array(),
                ),              
            ),
        ),
    ),*/
);