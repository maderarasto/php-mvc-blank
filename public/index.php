<?php

require_once '../lib/defines.php';
require_once '../lib/functions.php';
require_once '../lib/autoload.php';

use Lib\Application\Application;
use Lib\Application\ServiceContainer;

$app = new Application;
$app::loadEnvVariables();

// Register middlewares
$app->middlewares([]);

// Register services
ServiceContainer::register([]);

try {
    $app->handleRequest();
} catch (Exception $ex) {
    die($ex->getMessage());
}
