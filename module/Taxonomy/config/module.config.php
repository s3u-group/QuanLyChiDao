<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Taxonomy\Controller\Index' => 'Taxonomy\Controller\IndexController',
		),
	),

    'view_manager' => array(
        'template_path_stack' => array(
            'taxonomy' => __DIR__ . '/../view'
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'taxonomyService' => function ($sm) {
                $service = new \Taxonomy\Service\Taxonomy();
                $entityManager = $sm->get('Doctrine\ORM\EntityManager');
                $service->setEntityManager($entityManager);
                return $service;
            },
        ),
    ),

    'router' => array(
        'routes' => array(
            'taxonomies' => array(
                'type'    => 'literal', 
                'options' => array(
                    'route'    => '/taxonomy',                     
                    'defaults' => array(
                        '__NAMESPACE__' => 'Taxonomy\Controller',
                        'controller' => 'Index',
                        'action'     => 'admin',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(                    
                    'taxonomy' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '[/][:tax]',
                            'constraints' => array(                            
                                'tax'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),  
                            'defaults' => array(
                                'action'     => 'index',
                            )                          
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'crud' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '[/][:action][/:id]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[0-9]*'
                                    )
                                )
                            )
                        ),
                    ),
                    'not_found' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/not-found',
                            'defaults' => array(
                                'action' => 'notFound'
                            ),
                            'query' => array( 
                                //Query route deprecated as of ZF 2.1.4; use the "query" option of the HTTP router\'s assembling method instead
                                'type' => 'query',
                            ),
                        ),
                    )                   
                ),
            ),
         ),
     ),

	'doctrine' => array(
        'driver' => array(

            'taxonomy_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__.'/../src/Taxonomy/Entity',
                ),
            ),

            'orm_default' => array(
                'drivers' => array(

                    'Taxonomy\Entity' => 'taxonomy_annotation_driver'
                )
            )
        )
    ),
);