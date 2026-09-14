<?php

declare(strict_types=1);

/**
 * HTTP bootstrap: loads the autoloader, helpers and configuration, then
 * returns the booted application instance.
 */

use App\Core\App;

require __DIR__ . '/autoload.php';
require APP_PATH . '/Support/helpers.php';

return App::boot(require APP_PATH . '/config.php');
