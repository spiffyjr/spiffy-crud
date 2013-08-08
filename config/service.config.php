<?php

return array(
    'factories' => array(
        'DoctrineORMModule\Form\Annotation\AnnotationBuilder' => 'SpiffyCrud\Form\Annotation\AnnotationBuilderFactory',
        'SpiffyCrud\CrudManager'                              => 'SpiffyCrud\CrudManagerFactory',
        'SpiffyCrud\FormManager'                              => 'SpiffyCrud\FormManagerFactory',
        'SpiffyCrud\ModuleOptions'                            => 'SpiffyCrud\ModuleOptionsFactory',
    )
);