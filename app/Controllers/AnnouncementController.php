<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Validator;
use App\Repositories\AnnouncementRepository;

final class AnnouncementController extends Controller
{
    private readonly AnnouncementRepository $announcements;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->announcements = new AnnouncementRepository($this->db);
    }

    public function store(int $classroomId): void
    {
        $classroom = $this->classroomOrFail($classroomId, manage: true);
        $redirectTo = '/classrooms/' . $classroomId . '#tab-announcements';

        $validator = $this->validate($this->request->all());

        if ($validator->fails()) {
            $this->failWith($validator->errors(), $redirectTo, $this->oldInput());
        }

        $this->announcements->create((int) $classroom['id'], $this->auth->username(), $validator->value('content'));
        $this->success('Pengumuman berhasil ditambahkan.', $redirectTo);
    }

    public function update(int $announcementId): void
    {
        $announcement = $this->announcementOrFail($announcementId);
        $redirectTo = '/classrooms/' . $announcement['classroom_id'] . '#tab-announcements';

        $validator = $this->validate($this->request->all());

        if ($validator->fails()) {
            $this->failWith($validator->errors(), $redirectTo);
        }

        $this->announcements->update($announcementId, $validator->value('content'));
        $this->success('Pengumuman berhasil diperbarui.', $redirectTo);
    }

    public function delete(int $announcementId): void
    {
        $announcement = $this->announcementOrFail($announcementId);

        $this->announcements->delete($announcementId);
        $this->success('Pengumuman berhasil dihapus.', '/classrooms/' . $announcement['classroom_id'] . '#tab-announcements');
    }

    /** @param array<string, mixed> $input */
    private function validate(array $input): Validator
    {
        return (new Validator($input))
            ->required('content', 'Isi pengumuman')
            ->length('content', 'Isi pengumuman', 1, 2000);
    }

    /** @return array<string, mixed> The announcement, after verifying the user owns its classroom. */
    private function announcementOrFail(int $announcementId): array
    {
        return $this->classroomResourceOrFail($this->announcements->find($announcementId), 'Pengumuman tidak ditemukan.', manage: true);
    }
}
