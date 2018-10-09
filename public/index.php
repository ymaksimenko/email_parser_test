<?php
use App\Core\Application;


ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('BASE_DIR', dirname(__DIR__));
define("APP_PATH", BASE_DIR . "/app");
$config = include APP_PATH.'/config/app_config.php';

require BASE_DIR.'/vendor/autoload.php'; // include Composer's autoloader

require BASE_DIR . '/init_autoloader.php';

$application = new Application();

$application->start();