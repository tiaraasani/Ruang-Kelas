<?php

declare(strict_types=1);

use App\Core\Env;

Env::load(BASE_PATH . '/.env');

return [
    'app' => [
        'name' => Env::get('APP_NAME', 'Ruang Kelas'),
        'debug' => Env::bool('APP_DEBUG', false),
        // Optional override of the URL prefix, e.g. "/ruang-kelas/public". Auto-detected when empty.
        'base_path' => Env::get('APP_BASE_PATH') ?: null,
        'timezone' => Env::get('APP_TIMEZONE', 'Asia/Jakarta'),
    ],

    'database' => [
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => (int) Env::get('DB_PORT', '3306'),
        'name' => Env::get('DB_DATABASE', 'ruangkelas'),
        'user' => Env::get('DB_USERNAME', ''),
        'password' => Env::get('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
    ],

    'session' => [
        'name' => Env::get('SESSION_NAME', 'rk_session'),
        // Idle timeout in seconds.
        'lifetime' => (int) Env::get('SESSION_LIFETIME', '7200'),
        // Force the Secure cookie flag. Null means "detect from the connection".
        'secure' => Env::bool('SESSION_SECURE'),
    ],

    'auth' => [
        // Users who provide this code during registration become teachers. Empty disables it.
        'teacher_invite_code' => Env::get('TEACHER_INVITE_CODE', ''),
        // Transitional: accept legacy plaintext passwords once and rehash them. Keep false in production.
        'allow_legacy_plaintext' => Env::bool('AUTH_ALLOW_LEGACY_PLAINTEXT', false),
        'password_min_length' => 8,
        'max_attempts_per_user' => 5,
        'max_attempts_per_ip' => 20,
        'lockout_minutes' => 15,
    ],

    'uploads' => [
        // Files are stored outside the web root and served through DownloadController.
        'path' => STORAGE_PATH . '/uploads',
        'max_size' => 2 * 1024 * 1024,
        'allowed_extensions' => ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'zip'],
    ],
];
