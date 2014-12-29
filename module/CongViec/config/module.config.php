<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CongViec\Controller\Index' => 'CongViec\Controller\IndexController',
            'CongViec\Controller\PhanCong' => 'CongViec\Controller\PhanCongController',
            'CongViec\Controller\TheoDoi' => 'CongViec\Controller\TheoDoiController',

        ),
    ),
    'router' => array(
        'routes' => array(
            'cong_viec' => array(
                'type'    => 'literal', 
                'options' => array(
                    'route'    => '/cong-viec',                    
                    'defaults' => array(
                       '__NAMESPACE__'=>'CongViec\Controller',
                        'controller' => 'Index',
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