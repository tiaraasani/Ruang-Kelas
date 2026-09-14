<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Validator;
use App\Repositories\LoginAttemptRepository;
use App\Repositories\UserRepository;
use App\Support\Role;

/**
 * Registration and credential verification, including brute-force throttling.
 */
final class AuthService
{
    private const USERNAME_PATTERN = '/^[A-Za-z0-9_.]{3,30}$/';
    private const PASSWORD_MAX_LENGTH = 64;

    /** @param array<string, mixed> $config The "auth" configuration section. */
    public function __construct(
        private readonly UserRepository $users,
        private readonly LoginAttemptRepository $attempts,
        private readonly array $config,
    ) {
    }

    /**
     * Verify credentials. Failed attempts are counted per username and per IP address.
     *
     * @return array{user: array<string, mixed>|null, error: string|null}
     */
    public function attempt(string $username, string $password, string $ipAddress): array
    {
        $windowSeconds = (int) $this->config['lockout_minutes'] * 60;

        if (
            $this->attempts->countRecentForUsername($username, $windowSeconds) >= (int) $this->config['max_attempts_per_user']
            || $this->attempts->countRecentForIp($ipAddress, $windowSeconds) >= (int) $this->config['max_attempts_per_ip']
        ) {
            return [
                'user' => null,
                'error' => sprintf('Terlalu banyak percobaan login. Coba lagi dalam %d menit.', $this->config['lockout_minutes']),
            ];
        }

        $user = $this->users->findByUsername($username);

        if ($user === null || !$this->passwordMatches($user, $password)) {
            $this->attempts->record($username, $ipAddress);

            return ['user' => null, 'error' => 'Username atau password salah.'];
        }

        $this->attempts->clearForUsername($username);
        $this->attempts->prune($windowSeconds);

        return ['user' => $user, 'error' => null];
    }

    /**
     * Create a new account. Everyone registers as a student unless a valid teacher
     * invitation code is supplied.
     *
     * @param array<string, mixed> $input
     * @return array{user: array<string, mixed>|null, errors: string[]}
     */
    public function register(array $input): array
    {
        $validator = (new Validator($input))
            ->required('name', 'Nama')
            ->length('name', 'Nama', 2, 100)
            ->required('username', 'Username')
            ->pattern('username', self::USERNAME_PATTERN, 'Username hanya boleh berisi huruf, angka, titik, atau garis bawah (3-30 karakter).')
            ->required('password', 'Password')
            ->length('password', 'Password', (int) $this->config['password_min_length'], self::PASSWORD_MAX_LENGTH)
            ->same('password_confirmation', 'password', 'Konfirmasi password tidak sama.');

        $role = Role::STUDENT;
        $inviteCode = $validator->value('teacher_code');

        if ($inviteCode !== '') {
            $expectedCode = (string) $this->config['teacher_invite_code'];

            if ($expectedCode === '' || !hash_equals($expectedCode, $inviteCode)) {
                $validator->addError('teacher_code', 'Kode registrasi guru tidak valid.');
            } else {
                $role = Role::TEACHER;
            }
        }

        $username = $validator->value('username');

        if (!$validator->fails() && $this->users->exists($username)) {
            $validator->addError('username', 'Username sudah digunakan.');
        }

        if ($validator->fails()) {
            return ['user' => null, 'errors' => $validator->errors()];
        }

        $this->users->create(
            $username,
            $validator->value('name'),
            password_hash($validator->value('password'), PASSWORD_DEFAULT),
            $role,
        );

        return ['user' => $this->users->findByUsername($username), 'errors' => []];
    }

    /** @param array<string, mixed> $user */
    private function passwordMatches(array $user, string $password): bool
    {
        $storedHash = (string) $user['password_hash'];
        $hashInfo = password_get_info($storedHash);

        if (!empty($hashInfo['algo'])) {
            if (!password_verify($password, $storedHash)) {
                return false;
            }

            if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
                $this->users->updatePasswordHash($user['username'], password_hash($password, PASSWORD_DEFAULT));
            }

            return true;
        }

        // Transitional path for accounts created before password hashing existed.
        // Enabled only through AUTH_ALLOW_LEGACY_PLAINTEXT; the password is rehashed on success.
        if (!$this->config['allow_legacy_plaintext'] || !hash_equals($storedHash, $password)) {
            return false;
        }

        $this->users->updatePasswordHash($user['username'], password_hash($password, PASSWORD_DEFAULT));

        return true;
    }
}
