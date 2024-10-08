<?php

use App\Models\DropzoneHistory;
use Lib\Application\Application;
use Lib\Application\DB;


require_once '../lib/defines.php';
require_once '../lib/functions.php';
require_once '../lib/autoload.php';

$app = new Application;
$app::loadEnvVariables();

try {
    $app->handleRequest();
} catch (Exception $ex) {
    die($ex->getMessage());
}
