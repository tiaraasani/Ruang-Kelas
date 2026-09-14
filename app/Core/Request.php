<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Typed access to the current HTTP request and resolution of the route path.
 */
final class Request
{
    private readonly string $basePath;
    private readonly string $path;

    public function __construct(?string $basePathOverride = null)
    {
        $this->basePath = $basePathOverride !== null
            ? rtrim($basePathOverride, '/')
            : $this->detectBasePath();
        $this->path = $this->resolvePath();
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /** Route path relative to the application, always starting with "/". */
    public function path(): string
    {
        return $this->path;
    }

    /** URL prefix under which the public directory is served ("" when it is the web root). */
    public function basePath(): string
    {
        return $this->basePath;
    }

    public function isSecure(): bool
    {
        if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
            return true;
        }

        return (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443
            || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    }

    public function ip(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $_POST;
    }

    /** Trimmed string field from the request body. */
    public function post(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? null;

        return is_string($value) ? trim($value) : $default;
    }

    /** Untrimmed string field from the request body (for passwords). */
    public function raw(string $key): string
    {
        $value = $_POST[$key] ?? null;

        return is_string($value) ? $value : '';
    }

    /** @return array<mixed> */
    public function postArray(string $key): array
    {
        $value = $_POST[$key] ?? null;

        return is_array($value) ? $value : [];
    }

    public function queryInt(string $key, ?int $default = null): ?int
    {
        $value = filter_var($_GET[$key] ?? null, FILTER_VALIDATE_INT);

        return $value === false ? $default : $value;
    }

    /** @return array<string, mixed>|null A single uploaded file entry from $_FILES. */
    public function file(string $key): ?array
    {
        $file = $_FILES[$key] ?? null;

        if (!is_array($file) || !isset($file['error']) || is_array($file['error'])) {
            return null;
        }

        return $file;
    }

    private function detectBasePath(): string
    {
        if (PHP_SAPI === 'cli-server') {
            return '';
        }

        $scriptDirectory = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
        $requestPath = $this->requestPath();

        // The front controller lives in public/. The project may be served either from
        // public/ directly or from the project root through the root .htaccess rewrite.
        $candidates = [$scriptDirectory, preg_replace('#/public$#', '', $scriptDirectory) ?? $scriptDirectory];

        foreach ($candidates as $candidate) {
            if ($candidate === '' || $requestPath === $candidate || str_starts_with($requestPath, $candidate . '/')) {
                return $candidate;
            }
        }

        return '';
    }

    private function requestPath(): string
    {
        $path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

        return rawurldecode(is_string($path) ? $path : '/');
    }

    private function resolvePath(): string
    {
        $path = $this->requestPath();

        if ($this->basePath !== '' && str_starts_with($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
        }

        if (str_starts_with($path, '/index.php')) {
            $path = substr($path, strlen('/index.php'));
        }

        return '/' . trim($path, '/');
    }
}
