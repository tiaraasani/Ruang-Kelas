<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class MaterialRepository
{
    private const SELECT = 'SELECT m.id_materi AS id, m.id_kelas AS classroom_id, m.username AS author_username, u.nama AS author_name, m.judul_materi AS title, m.deskripsi AS description, m.file AS file_path FROM materi m JOIN user u ON u.username = m.username';

    public function __construct(private readonly Database $db)
    {
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(self::SELECT . ' WHERE m.id_materi = ?', [$id]);
    }

    /** @return array<int, array<string, mixed>> */
    public function forClassroom(int $classroomId): array
    {
        return $this->db->fetchAll(self::SELECT . ' WHERE m.id_kelas = ? ORDER BY m.id_materi DESC', [$classroomId]);
    }

    public function create(int $classroomId, string $authorUsername, string $title, string $description, string $filePath): int
    {
        return $this->db->insert(
            'INSERT INTO materi (username, id_kelas, judul_materi, deskripsi, file) VALUES (?, ?, ?, ?, ?)',
            [$authorUsername, $classroomId, $title, $description, $filePath],
        );
    }

    public function update(int $id, string $title, string $description): void
    {
        $this->db->execute(
            'UPDATE materi SET judul_materi = ?, deskripsi = ? WHERE id_materi = ?',
            [$title, $description, $id],
        );
    }

    public function delete(int $id): void
    {
        $this->db->execute('DELETE FROM materi WHERE id_materi = ?', [$id]);
    }
}
