<?php
return array(
    'router' => array(
        'routes' => include __DIR__ . '/route.config.php'
    ),

    'spiffy-crud' => array(
        'models' => array(),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'crudlist' => 'SpiffyCrud\View\Helper\Datatable'
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'spiffy-crud/crud/create'  => __DIR__ . '/../view/spiffy-crud/crud/create.phtml',
            'spiffy-crud/crud/details' => __DIR__ . '/../view/spiffy-crud/crud/details.phtml',
            'spiffy-crud/crud/index'   => __DIR__ . '/../view/spiffy-crud/crud/index.phtml',
            'spiffy-crud/crud/update'  => __DIR__ . '/../view/spiffy-crud/crud/update.phtml',
        )
    )
);