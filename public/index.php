<?php

use App\Models\DropzoneHistory;
use Lib\Application\Application;
use Lib\Application\DB;


require_once '../lib/defines.php';
require_once '../lib/functions.php';
require_once '../lib/autoload.php';

$app = new Application;
$app::loadEnvVariables();

$history = DropzoneHistory::create([
    'client_id' => 5,
    'client_contact_id' => 1,
    'project' => 'Novy2',
    'cv_file' => 'Profile3.pdf'
]);

//$history = DropzoneHistory::find(3);
////$history = new DropzoneHistory();
////$history->client_id = 1
//echo($history);

try {
    $app->handleRequest();
} catch (Exception $ex) {
    die($ex->getMessage());
}
