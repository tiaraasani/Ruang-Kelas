<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

/**
 * Failed login bookkeeping used for brute-force throttling (table: login_attempts).
 */
final class LoginAttemptRepository
{
    public function __construct(private readonly Database $db)
    {
    }

    public function countRecentForUsername(string $username, int $windowSeconds): int
    {
        $row = $this->db->fetchOne(
            'SELECT COUNT(*) AS total FROM login_attempts
             WHERE username = ? AND attempted_at >= DATE_SUB(NOW(), INTERVAL ? SECOND)',
            [$username, $windowSeconds],
        );

        return (int) ($row['total'] ?? 0);
    }

    public function countRecentForIp(string $ipAddress, int $windowSeconds): int
    {
        $row = $this->db->fetchOne(
            'SELECT COUNT(*) AS total FROM login_attempts
             WHERE ip_address = ? AND attempted_at >= DATE_SUB(NOW(), INTERVAL ? SECOND)',
            [$ipAddress, $windowSeconds],
        );

        return (int) ($row['total'] ?? 0);
    }

    public function record(string $username, string $ipAddress): void
    {
        $this->db->execute(
            'INSERT INTO login_attempts (username, ip_address, attempted_at) VALUES (?, ?, NOW())',
            [mb_substr($username, 0, 50), mb_substr($ipAddress, 0, 45)],
        );
    }

    public function clearForUsername(string $username): void
    {
        $this->db->execute('DELETE FROM login_attempts WHERE username = ?', [$username]);
    }

    /** Remove attempts older than the throttling window so the table stays small. */
    public function prune(int $olderThanSeconds): void
    {
        $this->db->execute(
            'DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL ? SECOND)',
            [$olderThanSeconds],
        );
    }
}
