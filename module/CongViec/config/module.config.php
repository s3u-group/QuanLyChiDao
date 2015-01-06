<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CongViec\Controller\CongVan' => 'CongViec\Controller\CongVanController',
            'CongViec\Controller\CongViec' => 'CongViec\Controller\CongViecController',
            'CongViec\Controller\PhanCong' => 'CongViec\Controller\PhanCongController',
            'CongViec\Controller\TheoDoi' => 'CongViec\Controller\TheoDoiController',

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
                ),
            ),
         ),
     ), 

    'view_manager' => array(
        'template_path_stack' => array(
            'cong_viec' => __DIR__ . '/../view',
        ),
    ),

    'view_helpers'=>array(
        'invokables'=>array( 
            'array_danh_sach_nguoi_thuc_hien'=>'CongViec\View\Helper\ArrayDanhSachNguoiThucHien',

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