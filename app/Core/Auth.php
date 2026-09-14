<?php

declare(strict_types=1);

namespace App\Core;

use App\Repositories\UserRepository;
use App\Support\Role;

/**
 * Tracks the authenticated user. Only the username is stored in the session;
 * the user record is reloaded from the database on each request.
 */
final class Auth
{
    private const SESSION_KEY = 'auth_username';

    /** @var array<string, mixed>|null */
    private ?array $user = null;
    private bool $resolved = false;

    public function __construct(private readonly Session $session, private readonly UserRepository $users)
    {
    }

    /** @return array<string, mixed>|null The current user without credential fields. */
    public function user(): ?array
    {
        if (!$this->resolved) {
            $this->resolved = true;
            $username = $this->session->get(self::SESSION_KEY);
            $record = is_string($username) ? $this->users->findByUsername($username) : null;

            if ($record === null) {
                $this->session->remove(self::SESSION_KEY);
            } else {
                unset($record['password_hash']);
                $this->user = $record;
            }
        }

        return $this->user;
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function username(): string
    {
        return (string) ($this->user()['username'] ?? '');
    }

    public function role(): int
    {
        return (int) ($this->user()['role'] ?? 0);
    }

    public function isTeacher(): bool
    {
        return $this->role() === Role::TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->role() === Role::STUDENT;
    }

    /** @param array<string, mixed> $user */
    public function login(array $user): void
    {
        $this->session->regenerate();
        $this->session->set(self::SESSION_KEY, $user['username']);

        unset($user['password_hash']);
        $this->user = $user;
        $this->resolved = true;
    }

    public function logout(): void
    {
        $this->session->invalidate();
        $this->user = null;
        $this->resolved = true;
    }
}
