<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Database;
use App\Repositories\AssignmentRepository;
use App\Repositories\GradeRepository;
use App\Repositories\SubmissionRepository;

final class GradeController extends Controller
{
    private const MIN_GRADE = 0;
    private const MAX_GRADE = 100;

    private readonly AssignmentRepository $assignments;
    private readonly SubmissionRepository $submissions;
    private readonly GradeRepository $grades;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->assignments = new AssignmentRepository($this->db);
        $this->submissions = new SubmissionRepository($this->db);
        $this->grades = new GradeRepository($this->db);
    }

    /** Save the grades posted as grades[<submission id>] = <0-100>. Empty values are skipped. */
    public function save(int $assignmentId): void
    {
        $assignment = $this->assignmentOrFail($assignmentId);
        $redirectTo = $this->gradesTab($assignment);

        $validSubmissionIds = array_map(
            static fn (array $submission): int => (int) $submission['id'],
            $this->submissions->forAssignment($assignmentId),
        );

        $grades = [];

        foreach ($this->request->postArray('grades') as $submissionId => $value) {
            if (!is_string($value) || trim($value) === '') {
                continue;
            }

            $submissionId = filter_var($submissionId, FILTER_VALIDATE_INT);
            $grade = filter_var(trim($value), FILTER_VALIDATE_INT, [
                'options' => ['min_range' => self::MIN_GRADE, 'max_range' => self::MAX_GRADE],
            ]);

            if ($submissionId === false || !in_array($submissionId, $validSubmissionIds, true)) {
                $this->failWith(['Data pengumpulan tidak valid.'], $redirectTo);
            }

            if ($grade === false) {
                $this->failWith([sprintf('Nilai harus berupa angka %d sampai %d.', self::MIN_GRADE, self::MAX_GRADE)], $redirectTo);
            }

            $grades[$submissionId] = $grade;
        }

        if ($grades === []) {
            $this->failWith(['Tidak ada nilai yang diisi.'], $redirectTo);
        }

        $this->db->transaction(function (Database $db) use ($grades): void {
            foreach ($grades as $submissionId => $grade) {
                $this->grades->save($submissionId, $grade);
            }
        });

        $this->success(sprintf('%d nilai berhasil disimpan.', count($grades)), $redirectTo);
    }

    public function destroy(int $assignmentId): void
    {
        $assignment = $this->assignmentOrFail($assignmentId);
        $removed = $this->grades->deleteForAssignment($assignmentId);

        $this->success(sprintf('%d nilai berhasil dihapus.', $removed), $this->gradesTab($assignment));
    }

    /** @return array<string, mixed> The assignment, after verifying the user owns its classroom. */
    private function assignmentOrFail(int $assignmentId): array
    {
        return $this->classroomResourceOrFail($this->assignments->find($assignmentId), 'Tugas tidak ditemukan.', manage: true);
    }

    /** @param array<string, mixed> $assignment */
    private function gradesTab(array $assignment): string
    {
        return sprintf('/classrooms/%d?assignment=%d#tab-grades', $assignment['classroom_id'], $assignment['id']);
    }
}
