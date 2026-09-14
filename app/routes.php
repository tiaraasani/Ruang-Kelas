<?php

declare(strict_types=1);

use App\Controllers\AnnouncementController;
use App\Controllers\AssignmentController;
use App\Controllers\AuthController;
use App\Controllers\ClassroomController;
use App\Controllers\CommentController;
use App\Controllers\DashboardController;
use App\Controllers\DownloadController;
use App\Controllers\GradeController;
use App\Controllers\MaterialController;
use App\Core\Router;

/**
 * Route table. The third argument is the access rule enforced by the router:
 * null (public), "guest", "auth", "teacher" or "student".
 * Every POST route is additionally protected by CSRF validation.
 */
return static function (Router $router): void {
    $router->get('/', [DashboardController::class, 'home']);

    $router->get('/login', [AuthController::class, 'showLogin'], 'guest');
    $router->post('/login', [AuthController::class, 'login'], 'guest');
    $router->get('/register', [AuthController::class, 'showRegister'], 'guest');
    $router->post('/register', [AuthController::class, 'register'], 'guest');
    $router->post('/logout', [AuthController::class, 'logout'], 'auth');

    $router->get('/dashboard', [DashboardController::class, 'index'], 'auth');

    $router->get('/classrooms/create', [ClassroomController::class, 'create'], 'teacher');
    $router->post('/classrooms', [ClassroomController::class, 'store'], 'teacher');
    $router->get('/classrooms/join', [ClassroomController::class, 'joinForm'], 'student');
    $router->post('/classrooms/join', [ClassroomController::class, 'join'], 'student');
    $router->get('/classrooms/{id}', [ClassroomController::class, 'show'], 'auth');

    $router->post('/classrooms/{id}/materials', [MaterialController::class, 'store'], 'teacher');
    $router->post('/materials/{id}/update', [MaterialController::class, 'update'], 'teacher');
    $router->post('/materials/{id}/delete', [MaterialController::class, 'delete'], 'teacher');

    $router->post('/classrooms/{id}/assignments', [AssignmentController::class, 'store'], 'teacher');
    $router->post('/assignments/{id}/update', [AssignmentController::class, 'update'], 'teacher');
    $router->post('/assignments/{id}/delete', [AssignmentController::class, 'delete'], 'teacher');
    $router->post('/assignments/{id}/submissions', [AssignmentController::class, 'submit'], 'student');

    $router->post('/classrooms/{id}/announcements', [AnnouncementController::class, 'store'], 'teacher');
    $router->post('/announcements/{id}/update', [AnnouncementController::class, 'update'], 'teacher');
    $router->post('/announcements/{id}/delete', [AnnouncementController::class, 'delete'], 'teacher');

    $router->post('/assignments/{id}/grades', [GradeController::class, 'save'], 'teacher');
    $router->post('/assignments/{id}/grades/delete', [GradeController::class, 'destroy'], 'teacher');

    $router->get('/comments', [CommentController::class, 'index'], 'auth');
    $router->post('/assignments/{id}/comments', [CommentController::class, 'store'], 'auth');

    $router->get('/files/materials/{id}', [DownloadController::class, 'material'], 'auth');
    $router->get('/files/assignments/{id}', [DownloadController::class, 'assignment'], 'auth');
    $router->get('/files/submissions/{id}', [DownloadController::class, 'submission'], 'auth');
};
