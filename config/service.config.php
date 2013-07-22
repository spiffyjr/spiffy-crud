<?php

return array(
    'factories' => array(
        'DoctrineORMModule\Form\Annotation\AnnotationBuilder' => 'SpiffyCrud\Form\Annotation\AnnotationBuilderFactory',
        'SpiffyCrud\Adapter\DoctrineObject'                   => 'SpiffyCrud\Adapter\DoctrineObjectFactory',
        'SpiffyCrud\CrudManager'                              => 'SpiffyCrud\CrudManagerFactory',
    )
);