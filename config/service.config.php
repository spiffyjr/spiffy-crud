<?php

return array(
    'factories' => array(
        'SpiffyCrudManager' => 'SpiffyCrud\Service\ManagerCrudFactory',

        'SpiffyCrudBuilderDoctrineOrm'     => 'SpiffyCrud\Service\BuilderDoctrineOrmFactory',
        'SpiffyCrudMapperDoctrineObject'   => 'SpiffyCrud\Service\MapperDoctrineObjectFactory',
    )
);