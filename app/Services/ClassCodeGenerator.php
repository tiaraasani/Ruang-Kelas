<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ClassroomRepository;
use RuntimeException;

/**
 * Generates unpredictable join codes for classrooms.
 */
final class ClassCodeGenerator
{
    /** Uppercase letters and digits without look-alike characters (0/O, 1/I). */
    private const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    private const LENGTH = 6;
    private const MAX_ATTEMPTS = 20;

    public function __construct(private readonly ClassroomRepository $classrooms)
    {
    }

    public function generate(): string
    {
        for ($attempt = 0; $attempt < self::MAX_ATTEMPTS; $attempt++) {
            $code = '';

            for ($i = 0; $i < self::LENGTH; $i++) {
                $code .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
            }

            if (!$this->classrooms->codeExists($code)) {
                return $code;
            }
        }

        throw new RuntimeException('Unable to generate a unique class code.');
    }
}
