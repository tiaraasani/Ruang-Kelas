<?php

declare(strict_types=1);

namespace App\Support;

/**
 * User roles as stored in the `role` table.
 */
final class Role
{
    public const TEACHER = 1;
    public const STUDENT = 2;

    public static function label(int $role): string
    {
        return match ($role) {
            self::TEACHER => 'Guru',
            self::STUDENT => 'Siswa',
            default => 'Pengguna',
        };
    }
}
