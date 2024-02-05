<?php

// autoload
spl_autoload_extensions('.php');
spl_autoload_register(function ($className) {
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $filename = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';
    if (is_readable($filename)) {
        require_once($filename);
    }
});
require_once("vendor/autoload.php");
