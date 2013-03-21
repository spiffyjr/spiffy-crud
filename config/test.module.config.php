<?php

$moduleConfig = include __DIR__ . '/module.config.php';
$testConfig   = array(
    'spiffycrud' => array(
        'forms'  => array(
            'invokables' => array(
                'simpleInvokable' => 'SpiffyCrudTest\Asset\SimpleForm',
            ),
            'factories' => array(
                'simpleFactory' => function($sm) {
                    return new \SpiffyCrudTest\Asset\SimpleForm();
                }
            )
        ),

        'models' => array(
            'invokables' => array(
                'simpleInvokable' => 'SpiffyCrudTest\Asset\SimpleModel',
            ),
            'factories' => array(
                'simpleFactory' => function($sm) {
                    return new \SpiffyCrudTest\Asset\SimpleModel();
                }
            )
        )
    )
);

return array_merge_recursive($moduleConfig, $testConfig);