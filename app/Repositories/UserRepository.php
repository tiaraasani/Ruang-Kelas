<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class UserRepository
{
    private const COLUMNS = 'username, nama AS name, password AS password_hash, id_role AS role';

    public function __construct(private readonly Database $db)
    {
    }

    /** @return array{username: string, name: string, password_hash: string, role: int}|null */
    public function findByUsername(string $username): ?array
    {
        $row = $this->db->fetchOne('SELECT ' . self::COLUMNS . ' FROM user WHERE username = ?', [$username]);

        return $row === null ? null : self::map($row);
    }

    public function exists(string $username): bool
    {
        return $this->db->fetchOne('SELECT 1 FROM user WHERE username = ?', [$username]) !== null;
    }

    public function create(string $username, string $name, string $passwordHash, int $role): void
    {
        $this->db->execute(
            'INSERT INTO user (username, nama, password, id_role) VALUES (?, ?, ?, ?)',
            [$username, $name, $passwordHash, $role],
        );
    }

    public function updatePasswordHash(string $username, string $passwordHash): void
    {
        $this->db->execute('UPDATE user SET password = ? WHERE username = ?', [$passwordHash, $username]);
    }

    /** @return array<int, array{username: string, password_hash: string}> */
    public function all(): array
    {
        return $this->db->fetchAll('SELECT username, password AS password_hash FROM user ORDER BY username');
    }

    /** @param array<string, mixed> $row */
    private static function map(array $row): array
    {
        return [
            'username' => (string) $row['username'],
            'name' => (string) $row['name'],
            'password_hash' => (string) $row['password_hash'],
            'role' => (int) $row['role'],
        ];
    }
}
