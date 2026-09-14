<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\FileStorage;
use App\Core\Validator;
use App\Repositories\AssignmentRepository;
use App\Repositories\SubmissionRepository;

final class AssignmentController extends Controller
{
    private readonly AssignmentRepository $assignments;
    private readonly SubmissionRepository $submissions;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->assignments = new AssignmentRepository($this->db);
        $this->submissions = new SubmissionRepository($this->db);
    }

    public function store(int $classroomId): void
    {
        $classroom = $this->classroomOrFail($classroomId, manage: true);
        $redirectTo = '/classrooms/' . $classroomId . '#tab-assignments';

        $validator = $this->validate($this->request->all());
        $file = $this->request->file('file');

        if ($file === null || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
            $validator->addError('file', 'File tugas wajib diunggah.');
        }

        if ($validator->fails()) {
            $this->failWith($validator->errors(), $redirectTo);
        }

        $filePath = $this->storeUpload($file, FileStorage::ASSIGNMENTS, $redirectTo);

        $this->assignments->create(
            (int) $classroom['id'],
            $this->auth->username(),
            $validator->value('title'),
            $validator->value('description'),
            self::deadlineFromInput($validator->value('deadline')),
            $filePath,
        );

        $this->success('Tugas berhasil ditambahkan.', $redirectTo);
    }

    public function update(int $assignmentId): void
    {
        $assignment = $this->assignmentOrFail($assignmentId, manage: true);
        $redirectTo = '/classrooms/' . $assignment['classroom_id'] . '#tab-assignments';

        $validator = $this->validate($this->request->all());

        if ($validator->fails()) {
            $this->failWith($validator->errors(), $redirectTo);
        }

        $this->assignments->update(
            $assignmentId,
            $validator->value('title'),
            $validator->value('description'),
            self::deadlineFromInput($validator->value('deadline')),
        );

        $this->success('Tugas berhasil diperbarui.', $redirectTo);
    }

    public function delete(int $assignmentId): void
    {
        $assignment = $this->assignmentOrFail($assignmentId, manage: true);
        $submissionFiles = $this->submissions->filePathsForAssignment($assignmentId);

        $this->assignments->delete($assignmentId);

        $storage = $this->storage();
        $storage->delete(FileStorage::ASSIGNMENTS, $assignment['file_path']);

        foreach ($submissionFiles as $filePath) {
            $storage->delete(FileStorage::SUBMISSIONS, $filePath);
        }

        $this->success('Tugas berhasil dihapus.', '/classrooms/' . $assignment['classroom_id'] . '#tab-assignments');
    }

    /** A student uploads (or replaces) their submission for an assignment. */
    public function submit(int $assignmentId): void
    {
        $assignment = $this->assignmentOrFail($assignmentId);
        $redirectTo = '/classrooms/' . $assignment['classroom_id'] . '#tab-assignments';
        $file = $this->request->file('file');

        if ($file === null || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
            $this->failWith(['File tugas wajib diunggah.'], $redirectTo);
        }

        $filePath = $this->storeUpload($file, FileStorage::SUBMISSIONS, $redirectTo);

        $username = $this->auth->username();
        $existing = $this->submissions->findForStudent($assignmentId, $username);

        if ($existing === null) {
            $this->submissions->create($assignmentId, $username, $filePath);
        } else {
            $this->submissions->replaceFile((int) $existing['id'], $filePath);
            $this->storage()->delete(FileStorage::SUBMISSIONS, $existing['file_path']);
        }

        $this->success('Tugas berhasil dikumpulkan.', $redirectTo);
    }

    /** @param array<string, mixed> $input */
    private function validate(array $input): Validator
    {
        return (new Validator($input))
            ->required('title', 'Judul tugas')
            ->length('title', 'Judul tugas', 3, 200)
            ->required('description', 'Deskripsi')
            ->length('description', 'Deskripsi', 1, 2000)
            ->date('deadline', 'Deadline');
    }

    /** Store deadlines as the end of the chosen day. */
    private static function deadlineFromInput(string $date): ?string
    {
        return $date === '' ? null : $date . ' 23:59:59';
    }

    /** @return array<string, mixed> The assignment, after checking access to its classroom. */
    private function assignmentOrFail(int $assignmentId, bool $manage = false): array
    {
        return $this->classroomResourceOrFail($this->assignments->find($assignmentId), 'Tugas tidak ditemukan.', $manage);
    }
}
