<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Auth;
use App\Core\Database;
use App\Core\FileStorage;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\UploadException;
use App\Repositories\ClassroomRepository;
use App\Services\ClassroomPolicy;
use App\Support\Role;

abstract class Controller
{
    protected readonly Request $request;
    protected readonly Session $session;
    protected readonly Auth $auth;
    protected readonly Database $db;
    protected readonly ClassroomRepository $classrooms;
    protected readonly ClassroomPolicy $policy;

    public function __construct(protected readonly App $app)
    {
        $this->request = $app->request;
        $this->session = $app->session;
        $this->auth = $app->auth;
        $this->db = $app->db;
        $this->classrooms = new ClassroomRepository($this->db);
        $this->policy = new ClassroomPolicy($this->classrooms);
    }

    /** Render a page inside the authenticated layout. */
    protected function render(string $template, array $data = []): void
    {
        $user = $this->auth->user();

        $data += [
            'auth' => $this->auth,
            'user' => $user,
            'sidebarClassrooms' => $user === null ? [] : $this->classroomsFor($user),
        ];

        echo $this->app->view->render($template, $data, 'layouts/app');
    }

    /** Render a page inside the guest (unauthenticated) layout. */
    protected function renderGuest(string $template, array $data = []): void
    {
        echo $this->app->view->render($template, $data, 'layouts/guest');
    }

    protected function redirect(string $path): never
    {
        Response::redirect(url($path));
    }

    /** @return array<string, mixed> The authenticated user (aborts with 401 otherwise). */
    protected function user(): array
    {
        return $this->auth->user() ?? Response::abort(401, 'Silakan login terlebih dahulu.');
    }

    /**
     * @param array<string, mixed> $user
     * @return array<int, array<string, mixed>> Classrooms the user owns (teacher) or is enrolled in (student).
     */
    protected function classroomsFor(array $user): array
    {
        return (int) $user['role'] === Role::TEACHER
            ? $this->classrooms->forTeacher((string) $user['username'])
            : $this->classrooms->forStudent((string) $user['username']);
    }

    /**
     * Load a classroom and verify the current user may view it, or manage it when
     * $manage is true (owning teacher only). Aborts with 404 or 403.
     *
     * @return array<string, mixed>
     */
    protected function classroomOrFail(int $classroomId, bool $manage = false): array
    {
        $classroom = $this->classrooms->find($classroomId) ?? Response::abort(404, 'Kelas tidak ditemukan.');
        $user = $this->user();

        $allowed = $manage
            ? $this->policy->isOwner($classroom, $user)
            : $this->policy->canView($classroom, $user);

        if (!$allowed) {
            Response::abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        return $classroom;
    }

    /**
     * Load a record that belongs to a classroom (materi, tugas, pengumuman) and verify
     * the current user may view or manage that classroom. Aborts with 404 or 403.
     *
     * @param array<string, mixed>|null $record
     * @return array<string, mixed>
     */
    protected function classroomResourceOrFail(?array $record, string $notFoundMessage, bool $manage = false): array
    {
        if ($record === null || ($record['classroom_id'] ?? null) === null) {
            Response::abort(404, $notFoundMessage);
        }

        $this->classroomOrFail((int) $record['classroom_id'], $manage);

        return $record;
    }

    protected function storage(): FileStorage
    {
        return new FileStorage(
            (string) config('uploads.path'),
            (int) config('uploads.max_size'),
            (array) config('uploads.allowed_extensions'),
        );
    }

    /**
     * Persist an already-present uploaded file, redirecting with the error message when rejected.
     *
     * @param array<string, mixed> $file
     */
    protected function storeUpload(array $file, string $category, string $redirectTo): string
    {
        try {
            return $this->storage()->store($file, $category);
        } catch (UploadException $exception) {
            $this->failWith([$exception->getMessage()], $redirectTo);
        }
    }

    /** Flash a success message and redirect. */
    protected function success(string $message, string $redirectTo): never
    {
        $this->session->flash('success', $message);
        $this->redirect($redirectTo);
    }

    /**
     * Flash validation errors (and optionally the submitted input) and redirect.
     *
     * @param string[] $errors
     * @param array<string, mixed> $oldInput
     */
    protected function failWith(array $errors, string $redirectTo, array $oldInput = []): never
    {
        $this->session->flash('errors', $errors);

        if ($oldInput !== []) {
            $this->session->flash('old', $oldInput);
        }

        $this->redirect($redirectTo);
    }

    /** @return array<string, string> Submitted string fields, excluding secrets. */
    protected function oldInput(): array
    {
        $excluded = ['password', 'password_confirmation', 'teacher_code', '_token'];
        $old = [];

        foreach ($this->request->all() as $key => $value) {
            if (is_string($value) && !in_array($key, $excluded, true)) {
                $old[(string) $key] = $value;
            }
        }

        return $old;
    }
}
