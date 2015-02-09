<?php

require __DIR__ . '/../vendor/autoload.php';

define('RESOURCES_PATH', __DIR__ . '/Resources');
define('VENDOR_BIN_DIR', __DIR__ . '/../vendor/bin');

spl_autoload_register(function($class) {
    if (strpos($class, 'Buster\Mocks') === 0 || strpos($class, 'Buster\Fixtures') === 0) {
        $parts = explode('\\', $class);
        array_shift($parts);
        require_once __DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    }
});