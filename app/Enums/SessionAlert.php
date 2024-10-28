<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Arr;

enum SessionAlert: string
{
    case Error = 'error';
    case Success = 'success';
    case Warning = 'warning';

    public function color(): string
    {
        return match ($this) {
            self::Error => 'danger',
            default => $this->value,
        };
    }

    public function exists(): bool
    {
        return session()->has($this->value);
    }

    public function message(bool $pull = true): ?string
    {
        return Arr::first($this->messages($pull));
    }

    public function messages(bool $pull = true): array
    {
        return $pull
            ? Arr::wrap(session()->pull($this->value))
            : Arr::wrap(session()->get($this->value));
    }

    public function forget(): void
    {
        session()->forget($this->value);
    }

    public function flash(?string $message): void
    {
        session()->flash($this->value, $message);
    }
}
