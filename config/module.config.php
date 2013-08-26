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
            'spiffy-crud/controller/read'   => __DIR__ . '/../view/spiffy-crud/controller/read.phtml',
            'spiffy-crud/controller/create' => __DIR__ . '/../view/spiffy-crud/controller/create.phtml',
            'spiffy-crud/controller/update' => __DIR__ . '/../view/spiffy-crud/controller/update.phtml',
            'spiffy-crud/controller/form'   => __DIR__ . '/../view/spiffy-crud/controller/form.phtml',
        )
    )
);