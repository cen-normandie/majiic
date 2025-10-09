<?php

// require composer autoload
//$envRoot = getenv('MPDF_ROOT');
//$path = $envRoot ?: __DIR__;

require_once '/var/www/html/majiic/vendor/autoload.php';

Tracy\Debugger::enable(Tracy\Debugger::DEVELOPMENT, __DIR__ . '/log');
Tracy\Debugger::$strictMode = true;
