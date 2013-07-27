<?php

return array(
    'spiffy_crud' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/crud',
            'defaults' => array(
                'controller' => 'SpiffyCrud\Controller\Crud',
                'action'     => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes'  => array(
            'details' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/:name',
                    'defaults' => array(
                        'controller' => 'SpiffyCrud\Controller\Crud',
                        'action'     => 'details'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'create' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/create',
                            'defaults' => array(
                                'controller' => 'SpiffyCrud\Controller\Crud',
                                'action'     => 'create'
                            ),
                        ),
                    ),
                    'update' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:id/update',
                            'defaults' => array(
                                'controller' => 'SpiffyCrud\Controller\Crud',
                                'action'     => 'update'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:id/delete',
                            'defaults' => array(
                                'controller' => 'SpiffyCrud\Controller\Crud',
                                'action'     => 'delete'
                            ),
                        ),
                    ),
                )
            )
        )
    ),
);