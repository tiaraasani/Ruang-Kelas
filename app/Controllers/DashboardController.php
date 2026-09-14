<?php

declare(strict_types=1);

namespace App\Controllers;

final class DashboardController extends Controller
{
    /** Landing route: send the visitor to the right place. */
    public function home(): void
    {
        $this->redirect($this->auth->check() ? '/dashboard' : '/login');
    }

    public function index(): void
    {
        $user = $this->user();

        $this->render('dashboard/index', [
            'title' => 'Beranda',
            'classrooms' => $this->classroomsFor($user),
        ]);
    }
}
