<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class SubmissionRepository
{
    private const SELECT = 'SELECT p.id_kumpul AS id, p.id_tugas AS assignment_id, p.username AS student_username, p.file_kumpul AS file_path, p.tanggal_kumpul AS submitted_at FROM pengumpulan p';

    public function __construct(private readonly Database $db)
    {
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(self::SELECT . ' WHERE p.id_kumpul = ?', [$id]);
    }

    /** @return array<string, mixed>|null */
    public function findForStudent(int $assignmentId, string $username): ?array
    {
        return $this->db->fetchOne(
            self::SELECT . ' WHERE p.id_tugas = ? AND p.username = ?',
            [$assignmentId, $username],
        );
    }

    /** @return array<int, array<string, mixed>> Submissions with student name and current grade. */
    public function forAssignment(int $assignmentId): array
    {
        return $this->db->fetchAll(
            'SELECT p.id_kumpul AS id, p.username AS student_username, u.nama AS student_name,
                    p.file_kumpul AS file_path, p.tanggal_kumpul AS submitted_at, n.angka_nilai AS grade
             FROM pengumpulan p
             JOIN user u ON u.username = p.username
             LEFT JOIN nilai n ON n.id_kumpul = p.id_kumpul
             WHERE p.id_tugas = ?
             ORDER BY u.nama',
            [$assignmentId],
        );
    }

    /** @return string[] Stored file paths of every submission for an assignment. */
    public function filePathsForAssignment(int $assignmentId): array
    {
        $rows = $this->db->fetchAll('SELECT file_kumpul FROM pengumpulan WHERE id_tugas = ?', [$assignmentId]);

        return array_values(array_filter(array_column($rows, 'file_kumpul'), 'is_string'));
    }

    public function create(int $assignmentId, string $username, string $filePath): int
    {
        return $this->db->insert(
            'INSERT INTO pengumpulan (id_tugas, file_kumpul, tanggal_kumpul, username) VALUES (?, ?, NOW(), ?)',
            [$assignmentId, $filePath, $username],
        );
    }

    public function replaceFile(int $id, string $filePath): void
    {
        $this->db->execute(
            'UPDATE pengumpulan SET file_kumpul = ?, tanggal_kumpul = NOW() WHERE id_kumpul = ?',
            [$filePath, $id],
        );
    }
}
