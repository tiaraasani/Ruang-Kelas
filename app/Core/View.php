<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

/**
 * Renders plain PHP templates. Templates must escape all dynamic output with e().
 */
final class View
{
    public function __construct(private readonly string $basePath)
    {
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = [], ?string $layout = 'layouts/app'): string
    {
        $content = $this->renderFile($template, $data);

        if ($layout === null) {
            return $content;
        }

        return $this->renderFile($layout, ['content' => $content] + $data);
    }

    /** Render a partial template in place. */
    public function insert(string $template, array $data = []): void
    {
        echo $this->renderFile($template, $data);
    }

    /** @param array<string, mixed> $data */
    public function renderFile(string $template, array $data = []): string
    {
        $file = $this->basePath . '/' . $template . '.php';

        if (!is_file($file)) {
            throw new RuntimeException(sprintf('View "%s" was not found.', $template));
        }

        extract($data, EXTR_SKIP);
        ob_start();

        try {
            require $file;

            return (string) ob_get_clean();
        } catch (Throwable $exception) {
            ob_end_clean();

            throw $exception;
        }
    }
}
