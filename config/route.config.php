<?php

return array(
    'spiffy_crud' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/crud',
            'defaults' => array(
                'controller' => 'SpiffyCrud\Controller\CrudController',
                'action'     => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes'  => array(
            'details' => array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/(?P<name>[\w|+]+)',
                    'spec' => '/%name%',
                    'defaults' => array(
                        'controller' => 'SpiffyCrud\Controller\CrudController',
                        'action'     => 'details'
                    ),
                ),
            ),
            'create' => array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/(?P<name>[\d\w]+)/create',
                    'spec' => '/%name%/create',
                    'defaults' => array(
                        'controller' => 'SpiffyCrud\Controller\CrudController',
                        'action'     => 'create'
                    ),
                ),
            ),
            'update' => array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/(?P<name>[\w|+]+)/(?P<id>[\d\w]+)/update',
                    'spec' => '/%name%/%id%/update',
                    'defaults' => array(
                        'controller' => 'SpiffyCrud\Controller\CrudController',
                        'action'     => 'update'
                    ),
                ),
            ),
            'delete' => array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/(?P<name>[\w|+]+)/(?P<id>[\d\w]+)/delete',
                    'spec' => '/%name%/%id%/delete',
                    'defaults' => array(
                        'controller' => 'SpiffyCrud\Controller\CrudController',
                        'action'     => 'delete'
                    ),
                ),
            ),
        )
    ),
);