<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'form_entities' => array(
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    0 => __DIR__ . '/../src/Form/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Form\\Entity' => 'form_entities',
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Form' => 'Form\\Controller\\FormController',
            'Admin\\Form' => 'Form\\Controller\\AdminController',
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\\Guard\\Controller' => array(
                0 => array(
                    'controller' => 'Form',
                    'roles' => array(
                        0 => 'user',
                        1 => 'guest',
                        2 => 'admin',
                    ),
                ),
                1 => array(
                    'controller' => 'Admin\\Form',
                    'roles' => array(
                        0 => 'user',
                        1 => 'admin',
                    ),
                ),
                2 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'manage',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                3 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'create-form',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                4 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'edit-form',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                5 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'create-section',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                6 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'edit-section',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                7 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'create-fieldset',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                8 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'create-fieldset-as-child',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                9 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'edit-fieldset-as-child',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                10 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'edit-fieldset',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                11 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'delete-fieldset',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                12 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'results',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                13 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'form-results',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                    ),
                ),
                14 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'create-element',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                15 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'delete-element',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                16 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'submitted-forms',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                    ),
                ),
                17 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'user-form-data',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                    ),
                ),
                18 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'reports',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                    ),
                ),
                19 => array(
                    'controller' => 'Admin\\Form',
                    'action' => 'download-csv',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                    ),
                ),
                20 => array(
                    'controller' => 'Form\\V1\\Rest\\ParentData\\Controller',
                    'roles' => array(
                        0 => 'guest',
                        1 => 'user',
                        2 => 'admin',
                    ),
                ),
                21 => array(
                    'controller' => 'Form\\V1\\Rest\\Element\\Controller',
                    'roles' => array(
                        0 => 'guest',
                        1 => 'user',
                        2 => 'admin',
                    ),
                ),
                22 => array(
                    'controller' => 'Form\\V1\\Rest\\Fieldset\\Controller',
                    'roles' => array(
                        0 => 'guest',
                        1 => 'user',
                        2 => 'admin',
                    ),
                ),
                23 => array(
                    'controller' => 'Form\\V1\\Rest\\Data\\Controller',
                    'roles' => array(
                        0 => 'guest',
                        1 => 'user',
                        2 => 'admin',
                    ),
                ),
                24 => array(
                    'controller' => 'Form\\V1\\Rest\\Form\\Controller',
                    'roles' => array(
                        0 => 'guest',
                        1 => 'user',
                        2 => 'admin',
                    ),
                ),
                25 => array(
                    'controller' => 'Form\\V1\\Rest\\Sync\\Controller',
                    'roles' => array(
                        1 => 'user',
                        2 => 'admin',
                    ),
                ),
                26 => array(
                    'controller' => 'Form\\V1\\Rest\\FormData\\Controller',
                    'roles' => array(
                        1 => 'user',
                        2 => 'admin',
                    ),
                ),
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'child_routes' => array(
                    'form' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/form[/:action[/:id]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\\Form',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
            'form' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/form[/:action[/:id]]',
                    'defaults' => array(
                        'controller' => 'Form',
                        'action' => 'index',
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ),
                ),
                'may_terminate' => true,
            ),
            'form.rest.doctrine.element' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/form/element[/:element_id]',
                    'defaults' => array(
                        'controller' => 'Form\\V1\\Rest\\Element\\Controller',
                    ),
                ),
            ),
            'form.rest.doctrine.fieldset' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/form/fieldset[/:fieldset_id]',
                    'defaults' => array(
                        'controller' => 'Form\\V1\\Rest\\Fieldset\\Controller',
                    ),
                ),
            ),
            'form.rest.doctrine.form' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/form/form[/:form_id]',
                    'defaults' => array(
                        'controller' => 'Form\\V1\\Rest\\Form\\Controller',
                    ),
                ),
            ),
            'form.rest.sync' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/form/:form_name/sync/parent-data[/:parent_data_id]',
                    'defaults' => array(
                        'controller' => 'Form\\V1\\Rest\\Sync\\Controller',
                    ),
                ),
            ),
            'form.rest.form-data' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/form/form[/:form_name]/data[/:form_data_id]',
                    'defaults' => array(
                        'controller' => 'Form\\V1\\Rest\\FormData\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'form' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'download/csv' => __DIR__ . '/../view/form/admin/download/csv.phtml',
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            1 => 'form.rest.doctrine.element',
            2 => 'form.rest.doctrine.fieldset',
            4 => 'form.rest.doctrine.form',
            5 => 'form.rest.sync',
            0 => 'form.rest.form-data',
        ),
    ),
    'zf-rest' => array(
        'Form\\V1\\Rest\\Element\\Controller' => array(
            'listener' => 'Form\\V1\\Rest\\Element\\ElementResource',
            'route_name' => 'form.rest.doctrine.element',
            'route_identifier_name' => 'element_id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'element',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Form\\Entity\\Element',
            'collection_class' => 'Form\\V1\\Rest\\Element\\ElementCollection',
            'service_name' => 'Element',
        ),
        'Form\\V1\\Rest\\Fieldset\\Controller' => array(
            'listener' => 'Form\\V1\\Rest\\Fieldset\\FieldsetResource',
            'route_name' => 'form.rest.doctrine.fieldset',
            'route_identifier_name' => 'fieldset_id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'fieldset',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Form\\Entity\\Fieldset',
            'collection_class' => 'Form\\V1\\Rest\\Fieldset\\FieldsetCollection',
            'service_name' => 'Fieldset',
        ),
        'Form\\V1\\Rest\\Form\\Controller' => array(
            'listener' => 'Form\\V1\\Rest\\Form\\FormResource',
            'route_name' => 'form.rest.doctrine.form',
            'route_identifier_name' => 'form_id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'form',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Form\\Entity\\Form',
            'collection_class' => 'Form\\V1\\Rest\\Form\\FormCollection',
            'service_name' => 'Form',
        ),
        'Form\\V1\\Rest\\Sync\\Controller' => array(
            'listener' => 'Form\\V1\\Rest\\Sync\\SyncResource',
            'route_name' => 'form.rest.sync',
            'route_identifier_name' => 'parent_data_id',
            'collection_name' => 'data',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'ids_to_exclude',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Form\\V1\\Rest\\Sync\\SyncEntity',
            'collection_class' => 'Form\\V1\\Rest\\Sync\\SyncCollection',
            'service_name' => 'Sync',
        ),
        'Form\\V1\\Rest\\FormData\\Controller' => array(
            'listener' => 'Form\\V1\\Rest\\FormData\\FormDataResource',
            'route_name' => 'form.rest.form-data',
            'route_identifier_name' => 'form_data_id',
            'collection_name' => 'data',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'order_by',
                1 => 'limit',
            ),
            'page_size' => 25,
            'page_size_param' => 'limit',
            'entity_class' => 'Form\\V1\\Rest\\FormData\\FormDataEntity',
            'collection_class' => 'Form\\V1\\Rest\\FormData\\FormDataCollection',
            'service_name' => 'FormData',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Form\\V1\\Rest\\Element\\Controller' => 'HalJson',
            'Form\\V1\\Rest\\Fieldset\\Controller' => 'HalJson',
            'Form\\V1\\Rest\\Form\\Controller' => 'HalJson',
            'Form\\V1\\Rest\\Sync\\Controller' => 'HalJson',
            'Form\\V1\\Rest\\FormData\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Form\\V1\\Rest\\Sync\\Controller' => array(
                0 => 'application/vnd.form.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Form\\V1\\Rest\\FormData\\Controller' => array(
                0 => 'application/vnd.form.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Form\\V1\\Rest\\Sync\\Controller' => array(
                0 => 'application/vnd.form.v1+json',
                1 => 'application/json',
            ),
            'Form\\V1\\Rest\\FormData\\Controller' => array(
                0 => 'application/vnd.form.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Form\\Entity\\Element' => array(
                'route_identifier_name' => 'element_id',
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.doctrine.element',
                'hydrator' => 'Form\\V1\\Rest\\Element\\ElementHydrator',
                'max_depth' => 2,
            ),
            'Form\\V1\\Rest\\Element\\ElementCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.doctrine.element',
                'is_collection' => true,
                'max_depth' => 2,
            ),
            'Form\\Entity\\Fieldset' => array(
                'route_identifier_name' => 'fieldset_id',
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.doctrine.fieldset',
                'hydrator' => 'Form\\V1\\Rest\\Fieldset\\FieldsetHydrator',
                'max_depth' => 2,
            ),
            'Form\\V1\\Rest\\Fieldset\\FieldsetCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.doctrine.fieldset',
                'is_collection' => true,
                'max_depth' => 2,
            ),
            'Form\\Entity\\Form' => array(
                'route_identifier_name' => 'form_id',
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.doctrine.form',
                'hydrator' => 'Form\\V1\\Rest\\Form\\FormHydrator',
                'max_depth' => 2,
            ),
            'Form\\V1\\Rest\\Form\\FormCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.doctrine.form',
                'is_collection' => true,
                'max_depth' => 2,
            ),
            'Form\\V1\\Rest\\Sync\\SyncEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.sync',
                'route_identifier_name' => 'sync_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Form\\V1\\Rest\\Sync\\SyncCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.sync',
                'route_identifier_name' => 'sync_id',
                'is_collection' => true,
            ),
            'Form\\Api\\V1\\Rest\\Sync\\Entity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.sync',
                'route_identifier_name' => 'parent_data_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ClassMethods',
            ),
            'Form\\Api\\V1\\Rest\\Sync\\Collection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.sync',
                'route_identifier_name' => 'parent_data_id',
                'is_collection' => true,
            ),
            'Form\\V1\\Rest\\Sync\\Entity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.sync',
                'route_identifier_name' => 'parent_data_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ClassMethods',
            ),
            'Form\\V1\\Rest\\Sync\\Collection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.sync',
                'route_identifier_name' => 'parent_data_id',
                'is_collection' => true,
            ),
            'Form\\V1\\Rest\\FormData\\FormDataEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.form-data',
                'route_identifier_name' => 'form_data_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ClassMethods',
            ),
            'Form\\V1\\Rest\\FormData\\FormDataCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'form.rest.form-data',
                'route_identifier_name' => 'form_data_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'doctrine-connected' => array(
            'Form\\V1\\Rest\\Element\\ElementResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'Form\\V1\\Rest\\Element\\ElementHydrator',
            ),
            'Form\\V1\\Rest\\Fieldset\\FieldsetResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'Form\\V1\\Rest\\Fieldset\\FieldsetHydrator',
            ),
            'Form\\V1\\Rest\\Form\\FormResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'Form\\V1\\Rest\\Form\\FormHydrator',
            ),
        ),
    ),
    'doctrine-hydrator' => array(
        'Form\\V1\\Rest\\Element\\ElementHydrator' => array(
            'entity_class' => 'Form\\Entity\\Element',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => false,
            'strategies' => array(),
            'use_generated_hydrator' => true,
        ),
        'Form\\V1\\Rest\\Fieldset\\FieldsetHydrator' => array(
            'entity_class' => 'Form\\Entity\\Fieldset',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => false,
            'strategies' => array(),
            'use_generated_hydrator' => true,
        ),
        'Form\\V1\\Rest\\Form\\FormHydrator' => array(
            'entity_class' => 'Form\\Entity\\Form',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => false,
            'strategies' => array(),
            'use_generated_hydrator' => true,
        ),
    ),
    'zf-content-validation' => array(
        'Form\\V1\\Rest\\Element\\Controller' => array(
            'input_filter' => 'Form\\V1\\Rest\\Element\\Validator',
        ),
        'Form\\V1\\Rest\\Form\\Controller' => array(
            'input_filter' => 'Form\\V1\\Rest\\Form\\Validator',
        ),
        'Form\\V1\\Rest\\Fieldset\\Controller' => array(
            'input_filter' => 'Form\\V1\\Rest\\Fieldset\\Validator',
        ),
        'Form\\V1\\Rest\\Sync\\Controller' => array(
            'input_filter' => 'Form\\V1\\Rest\\Sync\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Form\\V1\\Rest\\Element\\Validator' => array(
            0 => array(
                'name' => 'name',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
            1 => array(
                'name' => 'label',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
            2 => array(
                'name' => 'class',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
            3 => array(
                'name' => 'attributes',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            4 => array(
                'name' => 'options',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            5 => array(
                'name' => 'sort',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(),
            ),
        ),
        'Form\\V1\\Rest\\Form\\Validator' => array(
            0 => array(
                'name' => 'name',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
            1 => array(
                'name' => 'label',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
            2 => array(
                'name' => 'redirectUrl',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
        ),
        'Form\\V1\\Rest\\Fieldset\\Validator' => array(
            0 => array(
                'name' => 'name',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
            1 => array(
                'name' => 'class',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
            2 => array(
                'name' => 'label',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(),
            ),
            3 => array(
                'name' => 'sort',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(),
            ),
        ),
        'Form\\V1\\Rest\\Sync\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'id',
                'description' => 'The id of the current parent data to sync',
            ),
            1 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'user_id',
                'description' => 'The author of the item',
            ),
            2 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'date_updated',
                'description' => 'The latest updated date timestamp of this item',
            ),
            3 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'other_fields',
                'description' => 'The custom fields associated with this item. This is an array of key value pairs',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'force_update',
                'description' => 'Whether we do a force update or not',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Form\\V1\\Rest\\Sync\\SyncResource' => 'Form\\V1\\Rest\\Sync\\SyncResourceFactory',
            'Form\\V1\\Rest\\FormData\\FormDataResource' => 'Form\\V1\\Rest\\FormData\\FormDataResourceFactory',
        ),
    ),
);
