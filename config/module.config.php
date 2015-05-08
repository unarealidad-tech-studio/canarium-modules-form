<?php
return array(
	'doctrine' => array(
	  'driver' => array(
		'form_entities' => array(
		  'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
		  'cache' => 'array',
		  'paths' => array(__DIR__ . '/../src/Form/Entity')
		),
	
		'orm_default' => array(
		  'drivers' => array(
			'Form\Entity' => 'form_entities'
		  )
		)
	)
	),

    'controllers' => array(
        'invokables' => array(
			'Form' => 'Form\Controller\FormController',
			'Admin\Form' => 'Form\Controller\AdminController',
        ),
    ),
	
	'bjyauthorize' => array(
		'guards' => array(
			'BjyAuthorize\Guard\Route' => array(
				array('route' => 'form', 'roles' => array('user','guest')),
				array('route' => 'admin/form', 'roles' => array('admin')),
			),
			'BjyAuthorize\Guard\Controller' => array(
				array('controller' => 'Form',array(), 'roles' => array('user')),
            ),
		),
	),
	
	'router' => array(
        'routes' => array(
			'admin' => array(
                'child_routes' => array(
                    'form' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/form[/:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
								'controller'    => 'Admin\Form',
								'action'        => 'index',
                            ),
                        ),
                    ),
				),
			),
			
			'form' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/form[/:action[/:id]]',
					'defaults' => array(
						'controller'    => 'Form',
						'action'        => 'index',
					),
					'constraints' => array(
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]*',
					),
				),
				'may_terminate' => true,
			),
			
			
        ),
    ),
	
    'view_manager' => array(
        'template_path_stack' => array(
            'form' => __DIR__ . '/../view',
        ),
		'template_map' => array(
	        'download/csv' => 
	            __DIR__ . 
	            '/../view/form/admin/download/csv.phtml',
        ),
    ),
);