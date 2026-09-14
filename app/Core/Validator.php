<?php

declare(strict_types=1);

namespace App\Core;

use DateTimeImmutable;

/**
 * Small fluent validator producing user-facing messages. Keeps the first error per field.
 */
final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /** @param array<string, mixed> $data */
    public function __construct(private readonly array $data)
    {
    }

    public function value(string $field): string
    {
        $value = $this->data[$field] ?? '';

        return is_string($value) ? trim($value) : '';
    }

    public function required(string $field, string $label): self
    {
        if ($this->value($field) === '') {
            $this->addError($field, sprintf('%s wajib diisi.', $label));
        }

        return $this;
    }

    public function length(string $field, string $label, int $min, int $max): self
    {
        $value = $this->value($field);

        if ($value === '') {
            return $this;
        }

        $length = mb_strlen($value);

        if ($length < $min || $length > $max) {
            $this->addError($field, sprintf('%s harus terdiri dari %d sampai %d karakter.', $label, $min, $max));
        }

        return $this;
    }

    public function pattern(string $field, string $regex, string $message): self
    {
        $value = $this->value($field);

        if ($value !== '' && preg_match($regex, $value) !== 1) {
            $this->addError($field, $message);
        }

        return $this;
    }

    /** Accepts ISO dates (YYYY-MM-DD) as produced by <input type="date">. */
    public function date(string $field, string $label): self
    {
        $value = $this->value($field);

        if ($value === '') {
            return $this;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        if ($date === false || $date->format('Y-m-d') !== $value) {
            $this->addError($field, sprintf('%s harus berupa tanggal yang valid.', $label));
        }

        return $this;
    }

    public function same(string $field, string $otherField, string $message): self
    {
        if ($this->value($field) !== $this->value($otherField)) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function addError(string $field, string $message): void
    {
        $this->errors[$field] ??= $message;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    /** @return string[] */
    public function errors(): array
    {
        return array_values($this->errors);
    }
}
