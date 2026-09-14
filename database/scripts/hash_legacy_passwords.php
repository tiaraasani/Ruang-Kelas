#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * One-off migration: hash every password that is still stored in plaintext.
 *
 * Usage (from the project root, after configuring .env):
 *   php database/scripts/hash_legacy_passwords.php
 *
 * Rows that already contain a password_hash() value are left untouched, so the
 * script is safe to run more than once.
 */

use App\Core\Database;
use App\Repositories\UserRepository;

if (PHP_SAPI !== 'cli') {
    exit('This script can only be run from the command line.' . PHP_EOL);
}

require dirname(__DIR__, 2) . '/app/autoload.php';

$config = require APP_PATH . '/config.php';
$users = new UserRepository(new Database($config['database']));

$updated = 0;
$skipped = 0;

foreach ($users->all() as $user) {
    if (!empty(password_get_info($user['password_hash'])['algo'])) {
        $skipped++;
        continue;
    }

    $users->updatePasswordHash($user['username'], password_hash($user['password_hash'], PASSWORD_DEFAULT));
    $updated++;
}

printf('Hashed %d password(s), %d already hashed.%s', $updated, $skipped, PHP_EOL);
