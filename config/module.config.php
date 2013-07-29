<?php
return array(
    'router' => array(
        'routes' => include __DIR__ . '/route.config.php'
    ),

    'spiffy_crud' => array(
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

            'spiffy-crud/crud/create'  => __DIR__ . '/../view/spiffy-crud/crud/create.phtml',
            'spiffy-crud/crud/details' => __DIR__ . '/../view/spiffy-crud/crud/details.phtml',
            'spiffy-crud/crud/form'    => __DIR__ . '/../view/spiffy-crud/crud/form.phtml',
            'spiffy-crud/crud/index'   => __DIR__ . '/../view/spiffy-crud/crud/index.phtml',
            'spiffy-crud/crud/update'  => __DIR__ . '/../view/spiffy-crud/crud/update.phtml',
        )
    )
);