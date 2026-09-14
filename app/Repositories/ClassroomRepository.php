<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Support\Role;

final class ClassroomRepository
{
    private const SELECT = 'SELECT k.id_kelas AS id, k.namakelas AS name, k.mapel AS subject, k.kodeKelas AS code, k.username AS teacher_username FROM kelas k';

    public function __construct(private readonly Database $db)
    {
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(self::SELECT . ' WHERE k.id_kelas = ?', [$id]);
    }

    /** @return array<string, mixed>|null */
    public function findByCode(string $code): ?array
    {
        return $this->db->fetchOne(self::SELECT . ' WHERE k.kodeKelas = ?', [$code]);
    }

    public function codeExists(string $code): bool
    {
        return $this->db->fetchOne('SELECT 1 FROM kelas WHERE kodeKelas = ?', [$code]) !== null;
    }

    public function create(string $name, string $subject, string $code, string $teacherUsername): int
    {
        return $this->db->insert(
            'INSERT INTO kelas (namakelas, mapel, kodeKelas, username) VALUES (?, ?, ?, ?)',
            [$name, $subject, $code, $teacherUsername],
        );
    }

    /** @return array<int, array<string, mixed>> Classrooms owned by a teacher. */
    public function forTeacher(string $username): array
    {
        return $this->db->fetchAll(self::SELECT . ' WHERE k.username = ? ORDER BY k.namakelas', [$username]);
    }

    /** @return array<int, array<string, mixed>> Classrooms a student is enrolled in. */
    public function forStudent(string $username): array
    {
        return $this->db->fetchAll(
            self::SELECT . ' JOIN daftar_kelas d ON d.id_kelas = k.id_kelas WHERE d.username = ? ORDER BY k.namakelas',
            [$username],
        );
    }

    public function isEnrolled(int $classroomId, string $username): bool
    {
        return $this->db->fetchOne(
            'SELECT 1 FROM daftar_kelas WHERE id_kelas = ? AND username = ?',
            [$classroomId, $username],
        ) !== null;
    }

    public function enroll(int $classroomId, string $username): void
    {
        $this->db->execute('INSERT INTO daftar_kelas (id_kelas, username) VALUES (?, ?)', [$classroomId, $username]);
    }

    /** @return array<int, array{username: string, name: string}> */
    public function students(int $classroomId): array
    {
        return $this->db->fetchAll(
            'SELECT u.username, u.nama AS name
             FROM daftar_kelas d
             JOIN user u ON u.username = d.username
             WHERE d.id_kelas = ? AND u.id_role = ?
             ORDER BY u.nama',
            [$classroomId, Role::STUDENT],
        );
    }
}
