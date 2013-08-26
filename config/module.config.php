<?php
return array(
    'controllers' => include 'controller.config.php',

    'route_manager' => array(
        'invokables' => array(
            'crud' => 'SpiffyCrud\CrudRoute'
        )
    ),

    'service_manager' => include 'service.config.php',

    'spiffy_crud' => array(
        'adapters' => array(

        ),

        'manager' => array(
            'abstract_factories' => array(
                'SpiffyCrud\Model\AbstractFactory'
            ),
        ),

        'models' => array(

        ),
    ),

    'view_helpers' => array(
        'factories' => array(
            'spiffycrud' => 'SpiffyCrud\View\Helper\DatatableFactory'
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'spiffy-crud/crud/read'   => __DIR__ . '/../view/spiffy-crud/crud/read.phtml',
            'spiffy-crud/crud/create' => __DIR__ . '/../view/spiffy-crud/crud/create.phtml',
            'spiffy-crud/crud/update' => __DIR__ . '/../view/spiffy-crud/crud/update.phtml',
            'spiffy-crud/crud/form'   => __DIR__ . '/../view/spiffy-crud/crud/form.phtml',
        )
    )
);