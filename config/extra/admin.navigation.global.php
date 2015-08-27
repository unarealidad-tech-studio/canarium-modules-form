<?php
return array(
	'navigation' => array(
         'admin' => array(
			 array(
				'label' => 'Form Manager',
				'route' => 'admin/form',
				'resource' => 'admin',
				'controller' => 'Admin\Form',
				'icon' => 'fa fa-cogs',
				'pages' => array(
					array(
						'label' => 'Create',
						'route' => 'admin/form',
						'controller' => 'Admin\Form',
						'resource' => 'admin',
						'action'     => 'create-form',
				 		'icon' => 'fa fa-plus-circle',
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
						'resource' => 'admin',
						'action'     => 'submitted-forms',
				 		'icon' => 'fa fa-th-list',
					),
					array(
						'label' => 'Settings',
						'route' => 'admin/form',
						'controller' => 'Admin\Form',
						'resource' => 'superuser',
						'action'     => 'settings',
				 		'icon' => 'fa fa-cog',
					),
				),
             ),

			 array(
				'label' => 'Reports Manager',
				'route' => 'admin/form',
				'resource' => 'admin',
				'controller' => 'Admin\Form',
				'action'=> 'reports',
				'icon' => 'fa fa-bar-chart-o',
				'pages' => array(
				),
             ),
         ),
     ),
);