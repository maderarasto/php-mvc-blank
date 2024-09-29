<?php

use Lib\Application\FileSystem;

require_once '../lib/defines.php';
require_once '../lib/functions.php';
require_once '../lib/autoload.php';

FileSystem::ensureDirectory(CONTROLLERS_DIR . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'custom', 0755, true);