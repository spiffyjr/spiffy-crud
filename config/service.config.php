<?php

return array(
    'factories' => array(
        'SpiffyCrudManagerCrud'  => 'SpiffyCrud\Service\ManagerCrudFactory',
        'SpiffyCrudManagerForm'  => 'SpiffyCrud\Service\ManagerFormFactory',
        'SpiffyCrudManagerModel' => 'SpiffyCrud\Service\ManagerModelFactory',

        'SpiffyCrudMapperDoctrineObject' => 'SpiffyCrud\Service\MapperDoctrineObjectFactory'
    )
);