<?php

use Lib\Application\Application;
use Lib\Application\Controller;


require_once '../lib/defines.php';
require_once '../lib/functions.php';
require_once '../lib/autoload.php';

$app = new Application;
$app::loadEnvVariables();

try {
    $app->handleRequest();
} catch (Exception $ex) {
    echo 'ERROR';
}
