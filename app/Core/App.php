<?php

declare(strict_types=1);

namespace App\Core;

use App\Repositories\UserRepository;
use ErrorException;
use LogicException;
use Throwable;

/**
 * Application container: wires the core services together and dispatches the
 * current HTTP request.
 */
final class App
{
    private static ?App $instance = null;

    private function __construct(
        public readonly array $config,
        public readonly Request $request,
        public readonly Session $session,
        public readonly Database $db,
        public readonly Auth $auth,
        public readonly Csrf $csrf,
        public readonly View $view,
        public readonly Router $router,
    ) {
    }

    /** @param array<string, mixed> $config */
    public static function boot(array $config): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        date_default_timezone_set((string) $config['app']['timezone']);
        self::configureErrorHandling((bool) $config['app']['debug']);

        $request = new Request($config['app']['base_path']);
        $session = new Session($config['session'], $request->isSecure());
        $session->start();

        $db = new Database($config['database']);
        $router = new Router();

        self::$instance = new self(
            $config,
            $request,
            $session,
            $db,
            new Auth($session, new UserRepository($db)),
            new Csrf($session),
            new View(APP_PATH . '/Views'),
            $router,
        );

        $registerRoutes = require APP_PATH . '/routes.php';
        $registerRoutes($router);

        return self::$instance;
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            throw new LogicException('The application has not been booted.');
        }

        return self::$instance;
    }

    public function run(): void
    {
        $this->sendSecurityHeaders();

        try {
            $this->router->dispatch($this);
        } catch (HttpException $exception) {
            $this->renderError($exception->status(), $exception->getMessage());
        } catch (Throwable $exception) {
            error_log(sprintf(
                '%s: %s in %s:%d',
                $exception::class,
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
            ));

            $this->renderError(
                500,
                $this->config['app']['debug']
                    ? $exception->getMessage()
                    : 'Terjadi kesalahan pada server. Silakan coba lagi nanti.',
            );
        }
    }

    public function renderError(int $status, string $message = ''): void
    {
        http_response_code($status);

        echo $this->view->render(
            'errors/error',
            ['status' => $status, 'message' => $message, 'title' => 'Error ' . $status],
            'layouts/guest',
        );
    }

    private function sendSecurityHeaders(): void
    {
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: same-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        header('Cache-Control: no-store');
        header(
            "Content-Security-Policy: default-src 'self'; script-src 'self'; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
            . "font-src 'self' data: https://fonts.gstatic.com; img-src 'self' data:; "
            . "object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'",
        );

        if ($this->request->isSecure()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }

    private static function configureErrorHandling(bool $debug): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', $debug ? '1' : '0');
        ini_set('log_errors', '1');
        ini_set('error_log', STORAGE_PATH . '/logs/app.log');

        set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
            if ((error_reporting() & $severity) === 0) {
                return false;
            }

            throw new ErrorException($message, 0, $severity, $file, $line);
        });
    }
}
