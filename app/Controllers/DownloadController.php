<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\FileStorage;
use App\Core\Response;
use App\Repositories\AssignmentRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\SubmissionRepository;

/**
 * Serves uploaded files from outside the web root after an authorization check.
 */
final class DownloadController extends Controller
{
    private readonly MaterialRepository $materials;
    private readonly AssignmentRepository $assignments;
    private readonly SubmissionRepository $submissions;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->materials = new MaterialRepository($this->db);
        $this->assignments = new AssignmentRepository($this->db);
        $this->submissions = new SubmissionRepository($this->db);
    }

    public function material(int $materialId): void
    {
        $material = $this->materials->find($materialId) ?? Response::abort(404, 'Materi tidak ditemukan.');
        $this->classroomOrFail((int) $material['classroom_id']);

        $this->send(FileStorage::MATERIALS, $material['file_path']);
    }

    public function assignment(int $assignmentId): void
    {
        $assignment = $this->assignments->find($assignmentId) ?? Response::abort(404, 'Tugas tidak ditemukan.');
        $this->classroomOrFail((int) $assignment['classroom_id']);

        $this->send(FileStorage::ASSIGNMENTS, $assignment['file_path']);
    }

    /** A submission may be downloaded by its author or by the classroom's teacher. */
    public function submission(int $submissionId): void
    {
        $submission = $this->submissions->find($submissionId) ?? Response::abort(404, 'File pengumpulan tidak ditemukan.');
        $assignment = $this->assignments->find((int) $submission['assignment_id']) ?? Response::abort(404, 'Tugas tidak ditemukan.');
        $classroom = $this->classroomOrFail((int) $assignment['classroom_id']);
        $user = $this->user();

        if (!$this->policy->isOwner($classroom, $user) && $submission['student_username'] !== $user['username']) {
            Response::abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        $this->send(FileStorage::SUBMISSIONS, $submission['file_path']);
    }

    private function send(string $category, ?string $storedPath): never
    {
        $absolutePath = $this->storage()->resolve($category, $storedPath) ?? Response::abort(404, 'File tidak ditemukan.');
        $downloadName = FileStorage::displayName($storedPath);

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . (string) filesize($absolutePath));
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Cache-Control: private, no-store');

        readfile($absolutePath);
        exit;
    }
}
