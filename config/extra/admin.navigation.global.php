<?php
return array(
	'navigation' => array(
         'admin' => array(
			 array(
				'label' => 'Form Manager',
				'route' => 'admin/form',
				'resource' => 'owner',
				'controller' => 'Admin\Form',
				'icon' => 'fa fa-cogs',
				'pages' => array(
					array(
						'label' => 'Create',
						'route' => 'admin/form',
						'controller' => 'Admin\Form',
						'resource' => 'admin',
						'action'     => 'create-form',
				 		'icon' => 'fa fa-th-list',
					),
					array(
						'label' => 'List',
						'route' => 'admin/form',
						'controller' => 'Admin\Form',
						'resource' => 'admin',
						'action'     => 'manage',
				 		'icon' => 'fa fa-th-list',
					),
					array(
						'label' => 'Submitted Forms',
						'route' => 'admin/form',
						'controller' => 'Admin\Form',
						'resource' => 'owner',
						'action'     => 'submitted-forms',
				 		'icon' => 'fa fa-th-list',
					),
				),
             ),

			 array(
				'label' => 'Reports Manager',
				'route' => 'admin/form',
				'resource' => 'owner',
				'controller' => 'Admin\Form',
				'action'=> 'reports',
				'icon' => 'fa fa-bar-chart-o',
				'pages' => array(
				),
             ),             
         ),
     ),
);