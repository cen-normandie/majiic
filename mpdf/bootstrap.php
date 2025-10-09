<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo 'ola';

// require composer autoload
$envRoot = getenv('MPDF_ROOT');
$path = $envRoot ?: __DIR__;

require_once $path . '/../vendor/autoload.php';

Tracy\Debugger::enable(Tracy\Debugger::DEVELOPMENT, __DIR__ . '/log');
Tracy\Debugger::$strictMode = true;
