<?php

declare(strict_types=1);

/**
 * Path constants and the PSR-4 style autoloader for the App namespace.
 *
 * Shared by the HTTP bootstrap and by command line scripts.
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
    define('APP_PATH', __DIR__);
    define('PUBLIC_PATH', BASE_PATH . '/public');
    define('STORAGE_PATH', BASE_PATH . '/storage');
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = APP_PATH . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
