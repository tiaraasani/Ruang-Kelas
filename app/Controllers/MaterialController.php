<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\FileStorage;
use App\Core\Validator;
use App\Repositories\MaterialRepository;

final class MaterialController extends Controller
{
    private readonly MaterialRepository $materials;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->materials = new MaterialRepository($this->db);
    }

    public function store(int $classroomId): void
    {
        $classroom = $this->classroomOrFail($classroomId, manage: true);
        $redirectTo = '/classrooms/' . $classroomId . '#tab-materials';

        $validator = $this->validate($this->request->all());
        $file = $this->request->file('file');

        if ($file === null || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
            $validator->addError('file', 'File materi wajib diunggah.');
        }

        if ($validator->fails()) {
            $this->failWith($validator->errors(), $redirectTo);
        }

        $filePath = $this->storeUpload($file, FileStorage::MATERIALS, $redirectTo);

        $this->materials->create(
            (int) $classroom['id'],
            $this->auth->username(),
            $validator->value('title'),
            $validator->value('description'),
            $filePath,
        );

        $this->success('Materi berhasil ditambahkan.', $redirectTo);
    }

    public function update(int $materialId): void
    {
        $material = $this->materialOrFail($materialId);
        $redirectTo = '/classrooms/' . $material['classroom_id'] . '#tab-materials';

        $validator = $this->validate($this->request->all());

        if ($validator->fails()) {
            $this->failWith($validator->errors(), $redirectTo);
        }

        $this->materials->update($materialId, $validator->value('title'), $validator->value('description'));
        $this->success('Materi berhasil diperbarui.', $redirectTo);
    }

    public function delete(int $materialId): void
    {
        $material = $this->materialOrFail($materialId);

        $this->materials->delete($materialId);
        $this->storage()->delete(FileStorage::MATERIALS, $material['file_path']);

        $this->success('Materi berhasil dihapus.', '/classrooms/' . $material['classroom_id'] . '#tab-materials');
    }

    /** @param array<string, mixed> $input */
    private function validate(array $input): Validator
    {
        return (new Validator($input))
            ->required('title', 'Judul materi')
            ->length('title', 'Judul materi', 3, 200)
            ->required('description', 'Deskripsi')
            ->length('description', 'Deskripsi', 1, 2000);
    }

    /** @return array<string, mixed> The material, after verifying the user owns its classroom. */
    private function materialOrFail(int $materialId): array
    {
        return $this->classroomResourceOrFail($this->materials->find($materialId), 'Materi tidak ditemukan.', manage: true);
    }
}
