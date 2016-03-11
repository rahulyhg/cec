<?php
/**
 * Application Environments.
 */
define('ENV_DEVELOPMENT', 'development');
define('ENV_PRODUCTION', 'production');
define('ENV', (getenv('ENV') ? getenv('ENV') : ENV_PRODUCTION));

if (ENV == ENV_DEVELOPMENT) {
    ini_set('display_errors', true);
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_ALL);
}

/**
 * Versions.
 */
define('PHALCON_VERSION_REQUIRED', '2.0.8');
define('PHP_VERSION_REQUIRED', '5.4.14');

/**
 * Check phalcon framework installation.
 */
if (!extension_loaded('phalcon')) {
    printf('Install Phalcon framework %s', PHALCON_VERSION_REQUIRED);
    exit(1);
}

/**
 * Pathes.
 */
define('DS', DIRECTORY_SEPARATOR);
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__FILE__)));
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', dirname(__FILE__));
}

require_once ROOT_PATH . '/app/engine/Config.php';
require_once ROOT_PATH . '/app/engine/Exception.php';
require_once ROOT_PATH . '/app/engine/Init.php';
require_once ROOT_PATH . '/app/engine/Application.php';

if (php_sapi_name() !== 'cli') {
    $application = new Engine\Application();
} else {
    require_once ROOT_PATH . '/app/engine/Cli.php';
    $application = new Engine\Cli();
}

$application->run();
echo $application->getOutput();
