<?php
define("AUTOLOADER_PATH", '/vagrant');

return array(
    'modules' => array(
        'ZamBase',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);