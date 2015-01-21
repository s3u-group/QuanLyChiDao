<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CongViec\Controller\CongVan' => 'CongViec\Controller\CongVanController',
            'CongViec\Controller\CongViec' => 'CongViec\Controller\CongViecController',
            'CongViec\Controller\PhanCong' => 'CongViec\Controller\PhanCongController',
            'CongViec\Controller\TheoDoi' => 'CongViec\Controller\TheoDoiController',
            'CongViec\Controller\KetXuat' => 'CongViec\Controller\KetXuatController',
            'CongViec\Controller\TraCuu' => 'CongViec\Controller\TraCuuController',

        ),
    ),
    'router' => array(
        'routes' => array(
            'cong_van' => array(
                'type'    => 'literal', 
                'options' => array(
                    'route'    => '/cong-van',                    
                    'defaults' => array(
                       '__NAMESPACE__'=>'CongViec\Controller',
                        'controller' => 'CongVan',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'crud' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/][:action][/:id]',
                            'constraints' => array(                            
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'=>'[0-9]+',
                            ),                            
                        ),
                    ),            
                ),
            ),
            'cong_viec' => array(
                'type'    => 'literal', 
                'options' => array(
                    'route'    => '/cong-viec',                    
                    'defaults' => array(
                       '__NAMESPACE__'=>'CongViec\Controller',
                        'controller' => 'CongViec',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'crud' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/][:action][/:id]',
                            'constraints' => array(                            
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'=>'[0-9]+',
                            ),                            
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
                ),
            ),
            'theo_doi' => array(
                'type'    => 'literal', 
                'options' => array(
                    'route'    => '/theo-doi',                    
                    'defaults' => array(
                       '__NAMESPACE__'=>'CongViec\Controller',
                        'controller' => 'TheoDoi',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'crud' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/][:action][/:id]',
                            'constraints' => array(                            
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'=>'[0-9]+',
                            ),                            
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
                ),
            ),
            'tra_cuu' => array(
                'type'    => 'literal', 
                'options' => array(
                    'route'    => '/tra-cuu',                    
                    'defaults' => array(
                       '__NAMESPACE__'=>'CongViec\Controller',
                        'controller' => 'TraCuu',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'crud' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/][:action][/:id]',
                            'constraints' => array(                            
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'=>'[0-9]+',
                            ),                            
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
                ),
            ),
            'cong_viec_don_vi' => array(
                'type'    => 'literal', 
                'options' => array(
                    'route'    => '/cong-viec-don-vi',                    
                    'defaults' => array(
                       '__NAMESPACE__'=>'CongViec\Controller',
                        'controller' => 'TraCuu',
                        'action'     => 'cong-viec-don-vi',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'crud' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/][:action][/:id]',
                            'constraints' => array(                            
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'=>'[0-9]+',
                            ),                            
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
                ),
            ),
            'ket_xuat' => array(
                'type'    => 'literal', 
                'options' => array(
                    'route'    => '/ket-xuat',                    
                    'defaults' => array(
                       '__NAMESPACE__'=>'CongViec\Controller',
                        'controller' => 'KetXuat',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'crud' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/][:action][/:id]',
                            'constraints' => array(                            
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'=>'[0-9]+',
                            ),                            
                        ),
                    ),            
                ),
            ),
         ),
     ), 

    'view_manager' => array(
        'template_path_stack' => array(
            'cong_viec' => __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'invokables' => array(
        ),
        'factories' => array(
            'cong_viec' => function($sm){
                $congViec = new \CongViec\Service\CongViec();
                $congViec->setServiceManager($sm);
                return $congViec;
            }
        ),
    ),

    'view_helpers'=>array(
        'invokables'=>array( 
            'arrayDanhSachNguoiThucHien'=>'CongViec\View\Helper\ArrayDanhSachNguoiThucHien',
            'vaiTro' => 'CongViec\View\Helper\VaiTro',            
        ),
        'factories'=>array(
            'getVaiTroOfUser' => function($sm){
                $serviceManager=$sm->getServiceLocator();
                $getVaiTroOfUser=new \CongViec\View\Helper\GetVaiTroOfUser();
                $getVaiTroOfUser->setServiceManager($serviceManager);
                return $getVaiTroOfUser;
            },
        ),
    ),


    'doctrine' => array(
        'driver' => array(

            'cong_viec_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__.'/../src/CongViec/Entity',
                ),
            ),

            'orm_default' => array(
                'drivers' => array(
                    'CongViec\Entity' => 'cong_viec_annotation_driver'
                )
            )
        )
    ),
);