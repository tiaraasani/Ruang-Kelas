<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function redirect(string $url, int $status = 303): never
    {
        header('Location: ' . $url, true, $status);
        exit;
    }

    public static function abort(int $status, string $message = ''): never
    {
        throw new HttpException($status, $message);
    }
}
