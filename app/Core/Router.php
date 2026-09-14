<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Matches the request path against the route table and enforces the route's
 * access rule and CSRF protection before invoking the controller action.
 */
final class Router
{
    /** @var array<int, array{method: string, regex: string, handler: array{0: class-string, 1: string}, access: ?string}> */
    private array $routes = [];

    /** @param array{0: class-string, 1: string} $handler */
    public function get(string $pattern, array $handler, ?string $access = null): void
    {
        $this->add('GET', $pattern, $handler, $access);
    }

    /** @param array{0: class-string, 1: string} $handler */
    public function post(string $pattern, array $handler, ?string $access = null): void
    {
        $this->add('POST', $pattern, $handler, $access);
    }

    public function dispatch(App $app): void
    {
        $method = $app->request->method();
        $path = $app->request->path();
        $pathMatched = false;

        foreach ($this->routes as $route) {
            if (preg_match($route['regex'], $path, $matches) !== 1) {
                continue;
            }

            $pathMatched = true;

            if ($route['method'] !== $method) {
                continue;
            }

            $this->authorize($app, $route['access']);

            if ($method === 'POST' && !$app->csrf->validate($app->request->post('_token'))) {
                throw new HttpException(403, 'Token keamanan formulir tidak valid atau sudah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.');
            }

            [$class, $action] = $route['handler'];
            $parameters = array_map('intval', array_slice($matches, 1));

            (new $class($app))->{$action}(...$parameters);

            return;
        }

        throw new HttpException(
            $pathMatched ? 405 : 404,
            $pathMatched ? 'Metode permintaan tidak diizinkan.' : 'Halaman tidak ditemukan.',
        );
    }

    /** @param array{0: class-string, 1: string} $handler */
    private function add(string $method, string $pattern, array $handler, ?string $access): void
    {
        $regex = '#^' . preg_replace('#\{[a-zA-Z_]+\}#', '(\d+)', $pattern) . '$#';

        $this->routes[] = ['method' => $method, 'regex' => $regex, 'handler' => $handler, 'access' => $access];
    }

    private function authorize(App $app, ?string $access): void
    {
        if ($access === null) {
            return;
        }

        if ($access === 'guest') {
            if ($app->auth->check()) {
                Response::redirect(url('/dashboard'));
            }

            return;
        }

        if (!$app->auth->check()) {
            $app->session->flash('error', 'Silakan login terlebih dahulu.');
            Response::redirect(url('/login'));
        }

        if (($access === 'teacher' && !$app->auth->isTeacher()) || ($access === 'student' && !$app->auth->isStudent())) {
            throw new HttpException(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
    }
}
