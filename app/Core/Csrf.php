<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Synchronizer token pattern: one random token per session, validated on every POST.
 */
final class Csrf
{
    private const KEY = '_csrf_token';

    public function __construct(private readonly Session $session)
    {
    }

    public function token(): string
    {
        $token = $this->session->get(self::KEY);

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            $this->session->set(self::KEY, $token);
        }

        return $token;
    }

    public function validate(?string $token): bool
    {
        return is_string($token) && $token !== '' && hash_equals($this->token(), $token);
    }
}
