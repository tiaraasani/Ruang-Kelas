<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Repositories\LoginAttemptRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;

final class AuthController extends Controller
{
    private readonly AuthService $authService;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->authService = new AuthService(
            new UserRepository($this->db),
            new LoginAttemptRepository($this->db),
            (array) $app->config['auth'],
        );
    }

    public function showLogin(): void
    {
        $this->renderGuest('auth/login', ['title' => 'Login']);
    }

    public function login(): void
    {
        $username = $this->request->post('username');
        $password = $this->request->raw('password');

        if ($username === '' || $password === '') {
            $this->failWith(['Username dan password wajib diisi.'], '/login', ['username' => $username]);
        }

        $result = $this->authService->attempt($username, $password, $this->request->ip());

        if ($result['user'] === null) {
            $this->failWith([(string) $result['error']], '/login', ['username' => $username]);
        }

        $this->auth->login($result['user']);
        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        $this->renderGuest('auth/register', [
            'title' => 'Registrasi',
            'teacherRegistrationEnabled' => (string) config('auth.teacher_invite_code') !== '',
            'passwordMinLength' => (int) config('auth.password_min_length'),
        ]);
    }

    public function register(): void
    {
        $result = $this->authService->register($this->request->all());

        if ($result['errors'] !== []) {
            $this->failWith($result['errors'], '/register', $this->oldInput());
        }

        $this->success('Registrasi berhasil. Silakan login.', '/login');
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->success('Anda telah keluar.', '/login');
    }
}
