<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Amsterdam');

define('BASE_PATH', __DIR__);

define('APP_PATH', __DIR__ . '/core/autoloader.php');

require APP_PATH;

$autoload = new Autoloader();

# End of File