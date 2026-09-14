<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\FileStorage;

/**
 * Small global helpers used by controllers and views.
 */

function app(): App
{
    return App::instance();
}

/**
 * Read a configuration value using dot notation, e.g. config('app.name').
 */
function config(string $key, mixed $default = null): mixed
{
    $value = app()->config;

    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

/**
 * Escape a value for safe output inside HTML.
 */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

/**
 * Build an application URL from a route path, e.g. url('/classrooms/3', ['assignment' => 7]).
 */
function url(string $path = '/', array $query = []): string
{
    $url = app()->request->basePath() . '/' . ltrim($path, '/');

    if ($query !== []) {
        $url .= '?' . http_build_query($query);
    }

    return $url;
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(app()->csrf->token()) . '">';
}

/**
 * Previously submitted input flashed by the last request (used to refill forms).
 */
function old(string $key, string $default = ''): string
{
    $old = app()->session->getFlash('old');
    $value = is_array($old) ? ($old[$key] ?? $default) : $default;

    return is_string($value) ? $value : $default;
}

function format_datetime(?string $value, string $format = 'd M Y H:i'): string
{
    if ($value === null || $value === '') {
        return '-';
    }

    try {
        return (new DateTimeImmutable($value))->format($format);
    } catch (Exception) {
        return $value;
    }
}

function file_display_name(?string $storedPath): string
{
    return FileStorage::displayName($storedPath);
}
