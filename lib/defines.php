<?php

// Directories
defined('ROOT_DIR') || define('ROOT_DIR', dirname(__DIR__));
defined ('LIB_DIR') || define('LIB_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'lib');
defined('APP_DIR') || define('APP_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'app');
defined('CONTROLLERS_DIR') || define('CONTROLLERS_DIR', APP_DIR . DIRECTORY_SEPARATOR . 'controllers');
defined('VIEWS_DIR') || define('VIEWS_DIR', APP_DIR . DIRECTORY_SEPARATOR . 'views');

// Notations
defined('CASE_CAMEL') || define('CASE_CAMEL', 1);
defined('CASE_PASCAL') || define('CASE_PASCAL', 2);
defined('CASE_SNAKE') || define('CASE_SNAKE', 3);

// Settings
defined('IS_DEBUG') || define('IS_DEBUG', true);

// Utilities
defined('HTML_EOL') || define('HTML_EOL', '<br />');