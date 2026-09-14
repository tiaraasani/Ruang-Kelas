<?php

declare(strict_types=1);

/**
 * Front controller. Every request that does not match a static file is routed here.
 */

// Let the PHP built-in server (php -S) serve existing static files directly.
if (PHP_SAPI === 'cli-server') {
    $requestedFile = __DIR__ . rawurldecode((string) parse_url((string) $_SERVER['REQUEST_URI'], PHP_URL_PATH));

    if (is_file($requestedFile)) {
        return false;
    }
}

$app = require dirname(__DIR__) . '/app/bootstrap.php';
$app->run();
