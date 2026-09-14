<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ClassroomRepository;
use App\Support\Role;

/**
 * Authorization rules for classrooms. Every controller that touches classroom
 * data (materials, assignments, announcements, grades, files) goes through here.
 */
final class ClassroomPolicy
{
    public function __construct(private readonly ClassroomRepository $classrooms)
    {
    }

    /**
     * @param array<string, mixed> $classroom
     * @param array<string, mixed> $user
     */
    public function isOwner(array $classroom, array $user): bool
    {
        return (int) $user['role'] === Role::TEACHER
            && (string) $classroom['teacher_username'] === (string) $user['username'];
    }

    /**
     * The owning teacher and enrolled students may view a classroom.
     *
     * @param array<string, mixed> $classroom
     * @param array<string, mixed> $user
     */
    public function canView(array $classroom, array $user): bool
    {
        if ($this->isOwner($classroom, $user)) {
            return true;
        }

        return (int) $user['role'] === Role::STUDENT
            && $this->classrooms->isEnrolled((int) $classroom['id'], (string) $user['username']);
    }
}
