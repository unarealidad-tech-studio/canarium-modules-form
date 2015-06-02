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
			'BjyAuthorize\Guard\Controller' => array(
				array('controller' => 'Form', 'roles' => array('user', 'guest')),
                array('controller' => 'Admin\Form', 'roles' => array('user', 'admin')),
                array('controller' => 'Admin\Form', 'action'=>'manage', 'roles' => array('admin')),

                array('controller' => 'Admin\Form', 'action'=>'create-form', 'roles' => array('admin')),
                array('controller' => 'Admin\Form', 'action'=>'edit-form', 'roles' => array('admin')),

                array('controller' => 'Admin\Form', 'action'=>'create-section', 'roles' => array('admin')),
                array('controller' => 'Admin\Form', 'action'=>'edit-section', 'roles' => array('admin')),

                array('controller' => 'Admin\Form', 'action'=>'create-fieldset', 'roles' => array('admin')),
                array('controller' => 'Admin\Form', 'action'=>'create-fieldset-as-child', 'roles' => array('admin')),
                array('controller' => 'Admin\Form', 'action'=>'edit-fieldset-as-child', 'roles' => array('admin')),
                array('controller' => 'Admin\Form', 'action'=>'edit-fieldset', 'roles' => array('admin')),
                array('controller' => 'Admin\Form', 'action'=>'delete-fieldset', 'roles' => array('admin')),

                array('controller' => 'Admin\Form', 'action'=>'results', 'roles' => array('admin')),
                array('controller' => 'Admin\Form', 'action'=>'form-results', 'roles' => array('admin','owner')),

                array('controller' => 'Admin\Form', 'action'=>'create-element', 'roles' => array('admin')),
                array('controller' => 'Admin\Form', 'action'=>'delete-element', 'roles' => array('admin')),

                array('controller' => 'Admin\Form', 'action'=>'submitted-forms', 'roles' => array('admin','owner')),
                array('controller' => 'Admin\Form', 'action'=>'user-form-data', 'roles' => array('admin','owner')),

                array('controller' => 'Admin\Form', 'action'=>'reports', 'roles' => array('admin','owner')),
                array('controller' => 'Admin\Form', 'action'=>'download-csv', 'roles' => array('admin','owner')),
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