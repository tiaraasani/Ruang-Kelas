<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class GradeRepository
{
    public function __construct(private readonly Database $db)
    {
    }

    /** Insert or update the grade of a submission (nilai.id_kumpul is unique). */
    public function save(int $submissionId, int $grade): void
    {
        $this->db->execute(
            'INSERT INTO nilai (id_kumpul, angka_nilai) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE angka_nilai = VALUES(angka_nilai)',
            [$submissionId, $grade],
        );
    }

    /** @return int Number of grades removed. */
    public function deleteForAssignment(int $assignmentId): int
    {
        return $this->db->execute(
            'DELETE n FROM nilai n JOIN pengumpulan p ON p.id_kumpul = n.id_kumpul WHERE p.id_tugas = ?',
            [$assignmentId],
        );
    }
}
