<?php

require __DIR__ . '/../vendor/autoload.php';

define('RESOURCES_PATH', __DIR__ . '/Resources');

spl_autoload_register(function($class) {
    if (strpos($class, 'Buster\Mocks') === 0 || strpos($class, 'Buster\Fixtures') === 0) {
        $parts = explode('\\', $class);
        array_shift($parts);
        require_once __DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    }
});