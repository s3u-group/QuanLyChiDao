<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'CongViec\Controller\Index' => 'CongViec\Controller\IndexController',

         ),
     ),

     // The following section is new and should be added to your file
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
    
 )
     );
?>