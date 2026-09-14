<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class AnnouncementRepository
{
    private const SELECT = 'SELECT p.id_pengumuman AS id, p.id_kelas AS classroom_id, p.username AS author_username, u.nama AS author_name, p.isi_pengumuman AS content, p.tanggal_pengumuman AS published_at FROM pengumuman p LEFT JOIN user u ON u.username = p.username';

    public function __construct(private readonly Database $db)
    {
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(self::SELECT . ' WHERE p.id_pengumuman = ?', [$id]);
    }

    /** @return array<int, array<string, mixed>> */
    public function forClassroom(int $classroomId): array
    {
        return $this->db->fetchAll(
            self::SELECT . ' WHERE p.id_kelas = ? ORDER BY p.tanggal_pengumuman DESC, p.id_pengumuman DESC',
            [$classroomId],
        );
    }

    public function create(int $classroomId, string $authorUsername, string $content): int
    {
        return $this->db->insert(
            'INSERT INTO pengumuman (isi_pengumuman, tanggal_pengumuman, id_kelas, username) VALUES (?, NOW(), ?, ?)',
            [$content, $classroomId, $authorUsername],
        );
    }

    public function update(int $id, string $content): void
    {
        $this->db->execute('UPDATE pengumuman SET isi_pengumuman = ? WHERE id_pengumuman = ?', [$content, $id]);
    }

    public function delete(int $id): void
    {
        $this->db->execute('DELETE FROM pengumuman WHERE id_pengumuman = ?', [$id]);
    }
}
