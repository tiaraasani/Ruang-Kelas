<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Session wrapper with hardened cookie settings, idle expiry and flash data.
 */
final class Session
{
    private const FLASH_KEY = '_flash';
    private const ACTIVITY_KEY = '_last_activity';

    /** @var array<string, mixed> Flash data available during the current request only. */
    private array $flashNow = [];

    public function __construct(private readonly array $config, private readonly bool $secureConnection)
    {
    }

    public function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $lifetime = max(60, (int) $this->config['lifetime']);
        $secure = $this->config['secure'] ?? $this->secureConnection;

        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.gc_maxlifetime', (string) $lifetime);

        session_name((string) $this->config['name']);
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => (bool) $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();

        $this->expireWhenIdle($lifetime);

        $this->flashNow = is_array($_SESSION[self::FLASH_KEY] ?? null) ? $_SESSION[self::FLASH_KEY] : [];
        unset($_SESSION[self::FLASH_KEY]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /** Issue a new session id (call after any privilege change such as login). */
    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /** Drop all session data and issue a fresh id (logout). */
    public function invalidate(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
        $this->flashNow = [];
    }

    /** Store a value that will be available during the next request only. */
    public function flash(string $key, mixed $value): void
    {
        $_SESSION[self::FLASH_KEY][$key] = $value;
    }

    public function getFlash(string $key, mixed $default = null): mixed
    {
        return $this->flashNow[$key] ?? $default;
    }

    private function expireWhenIdle(int $lifetime): void
    {
        $lastActivity = $_SESSION[self::ACTIVITY_KEY] ?? null;

        if (is_int($lastActivity) && time() - $lastActivity > $lifetime) {
            $_SESSION = [];
            session_regenerate_id(true);
        }

        $_SESSION[self::ACTIVITY_KEY] = time();
    }
}
