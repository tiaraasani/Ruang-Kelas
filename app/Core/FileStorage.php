<?php

declare(strict_types=1);

namespace App\Core;

use finfo;
use RuntimeException;

/**
 * Stores uploaded files outside the web root under a random name and validates
 * both the extension and the detected MIME type.
 */
final class FileStorage
{
    public const MATERIALS = 'materials';
    public const ASSIGNMENTS = 'assignments';
    public const SUBMISSIONS = 'submissions';

    private const CATEGORIES = [self::MATERIALS, self::ASSIGNMENTS, self::SUBMISSIONS];

    /** @var array<string, string[]> Accepted MIME types per extension, as reported by finfo. */
    private const MIME_TYPES = [
        'pdf' => ['application/pdf'],
        'doc' => ['application/msword', 'application/vnd.ms-office', 'application/x-ole-storage', 'application/CDFV2'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
        'ppt' => ['application/vnd.ms-powerpoint', 'application/vnd.ms-office', 'application/x-ole-storage', 'application/CDFV2'],
        'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'],
        'xls' => ['application/vnd.ms-excel', 'application/vnd.ms-office', 'application/x-ole-storage', 'application/CDFV2'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
        'txt' => ['text/plain'],
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'zip' => ['application/zip', 'application/x-zip-compressed'],
    ];

    /** @param string[] $allowedExtensions */
    public function __construct(
        private readonly string $root,
        private readonly int $maxSize,
        private readonly array $allowedExtensions,
    ) {
    }

    /**
     * Validate and persist an uploaded file.
     *
     * @param array<string, mixed> $file One entry of $_FILES.
     * @return string Stored path relative to the upload root ("category/name.ext").
     * @throws UploadException When the file is rejected.
     */
    public function store(array $file, string $category): string
    {
        $this->assertCategory($category);

        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($error !== UPLOAD_ERR_OK) {
            throw new UploadException(self::errorMessage($error));
        }

        $temporaryPath = (string) ($file['tmp_name'] ?? '');

        if ($temporaryPath === '' || !is_uploaded_file($temporaryPath)) {
            throw new UploadException('File yang diunggah tidak valid.');
        }

        $size = (int) ($file['size'] ?? 0);

        if ($size <= 0) {
            throw new UploadException('File yang diunggah kosong.');
        }

        if ($size > $this->maxSize) {
            throw new UploadException(sprintf('Ukuran file melebihi batas maksimum %s.', self::humanSize($this->maxSize)));
        }

        $originalName = basename(str_replace('\\', '/', (string) ($file['name'] ?? '')));
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($extension === '' || !in_array($extension, $this->allowedExtensions, true) || !isset(self::MIME_TYPES[$extension])) {
            throw new UploadException('Tipe file tidak diizinkan. Tipe yang diperbolehkan: ' . implode(', ', $this->allowedExtensions) . '.');
        }

        $detectedMime = (new finfo(FILEINFO_MIME_TYPE))->file($temporaryPath) ?: '';

        if (!in_array($detectedMime, self::MIME_TYPES[$extension], true)) {
            throw new UploadException('Isi file tidak sesuai dengan ekstensinya.');
        }

        $directory = $this->root . '/' . $category;

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Upload directory "%s" could not be created.', $directory));
        }

        $storedName = bin2hex(random_bytes(8)) . '_' . self::sanitizeName(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
        $destination = $directory . '/' . $storedName;

        if (!move_uploaded_file($temporaryPath, $destination)) {
            throw new UploadException('File gagal disimpan. Silakan coba lagi.');
        }

        @chmod($destination, 0644);

        return $category . '/' . $storedName;
    }

    /**
     * Absolute path of a stored file, or null when it does not exist.
     * Only the base name of the stored path is used, so traversal is impossible.
     */
    public function resolve(string $category, ?string $storedPath): ?string
    {
        $this->assertCategory($category);

        if ($storedPath === null || $storedPath === '') {
            return null;
        }

        $directory = realpath($this->root . '/' . $category);

        if ($directory === false) {
            return null;
        }

        $fileName = basename(str_replace('\\', '/', $storedPath));
        $absolute = realpath($directory . DIRECTORY_SEPARATOR . $fileName);

        if ($absolute === false || !is_file($absolute) || !str_starts_with($absolute, $directory . DIRECTORY_SEPARATOR)) {
            return null;
        }

        return $absolute;
    }

    public function delete(string $category, ?string $storedPath): void
    {
        $absolute = $this->resolve($category, $storedPath);

        if ($absolute !== null) {
            @unlink($absolute);
        }
    }

    /** Original-looking file name for display, without the random prefix. */
    public static function displayName(?string $storedPath): string
    {
        if ($storedPath === null || $storedPath === '') {
            return '';
        }

        $fileName = basename(str_replace('\\', '/', $storedPath));

        return preg_replace('/^[0-9a-f]{16}_/', '', $fileName) ?? $fileName;
    }

    private static function sanitizeName(string $name): string
    {
        $name = preg_replace('/[^A-Za-z0-9._-]+/', '-', $name) ?? '';
        $name = trim($name, '.-');

        return $name === '' ? 'file' : substr($name, 0, 80);
    }

    private function assertCategory(string $category): void
    {
        if (!in_array($category, self::CATEGORIES, true)) {
            throw new RuntimeException(sprintf('Unknown upload category "%s".', $category));
        }
    }

    private static function errorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas yang diizinkan.',
            UPLOAD_ERR_PARTIAL => 'File hanya terunggah sebagian. Silakan coba lagi.',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang dipilih.',
            default => 'Terjadi kesalahan saat mengunggah file.',
        };
    }

    private static function humanSize(int $bytes): string
    {
        return $bytes >= 1048576
            ? sprintf('%d MB', intdiv($bytes, 1048576))
            : sprintf('%d KB', intdiv($bytes, 1024));
    }
}
