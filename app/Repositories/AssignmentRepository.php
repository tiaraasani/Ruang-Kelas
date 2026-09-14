<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class AssignmentRepository
{
    private const SELECT = 'SELECT t.id_tugas AS id, t.id_kelas AS classroom_id, t.username AS author_username, u.nama AS author_name, t.judul_tugas AS title, t.deskripsi_tugas AS description, t.deadline, t.file_tugas AS file_path, t.tanggal_upload AS created_at FROM tugas t JOIN user u ON u.username = t.username';

    public function __construct(private readonly Database $db)
    {
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(self::SELECT . ' WHERE t.id_tugas = ?', [$id]);
    }

    /** @return array<int, array<string, mixed>> */
    public function forClassroom(int $classroomId): array
    {
        return $this->db->fetchAll(
            self::SELECT . ' WHERE t.id_kelas = ? ORDER BY t.tanggal_upload DESC, t.id_tugas DESC',
            [$classroomId],
        );
    }

    public function create(
        int $classroomId,
        string $authorUsername,
        string $title,
        string $description,
        ?string $deadline,
        string $filePath,
    ): int {
        return $this->db->insert(
            'INSERT INTO tugas (judul_tugas, deskripsi_tugas, id_kelas, deadline, file_tugas, tanggal_upload, username)
             VALUES (?, ?, ?, ?, ?, NOW(), ?)',
            [$title, $description, $classroomId, $deadline, $filePath, $authorUsername],
        );
    }

    public function update(int $id, string $title, string $description, ?string $deadline): void
    {
        $this->db->execute(
            'UPDATE tugas SET judul_tugas = ?, deskripsi_tugas = ?, deadline = ? WHERE id_tugas = ?',
            [$title, $description, $deadline, $id],
        );
    }

    public function delete(int $id): void
    {
        $this->db->execute('DELETE FROM tugas WHERE id_tugas = ?', [$id]);
    }
}
