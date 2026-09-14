<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class CommentRepository
{
    public function __construct(private readonly Database $db)
    {
    }

    /** @return array<int, array<string, mixed>> Comments on an assignment, oldest first. */
    public function forAssignment(int $assignmentId): array
    {
        return $this->db->fetchAll(
            'SELECT c.id_komentar AS id, c.isi_komentar AS content, c.tanggal AS created_at,
                    c.username AS author_username, u.nama AS author_name
             FROM komentar c
             JOIN user u ON u.username = c.username
             WHERE c.id_tugas = ?
             ORDER BY c.tanggal ASC, c.id_komentar ASC',
            [$assignmentId],
        );
    }

    public function create(int $assignmentId, string $authorUsername, string $content): int
    {
        return $this->db->insert(
            'INSERT INTO komentar (isi_komentar, id_tugas, username, tanggal) VALUES (?, ?, ?, NOW())',
            [$content, $assignmentId, $authorUsername],
        );
    }
}
