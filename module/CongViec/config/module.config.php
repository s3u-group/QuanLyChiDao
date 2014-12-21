<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CongViec\Controller\Index' => 'CongViec\Controller\IndexController',
            'CongViec\Controller\PhanCong' => 'CongViec\Controller\PhanCongController',
            'CongViec\Controller\TheoDoi' => 'CongViec\Controller\TheoDoiController',

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