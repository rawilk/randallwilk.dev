<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class UniqueEmail implements ValidationRule
{
    protected ?User $user = null;

    public static function make(): static
    {
        return new static;
    }

    public function withUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->isSameEmailAsEditedUser($value)) {
            return;
        }

        $exists = User::query()
            ->withoutGlobalScopes()
            ->emailInsensitive((string) $value)
            ->exists();

        if ($exists) {
            $fail('validation.unique')->translate();
        }
    }

    protected function isSameEmailAsEditedUser(mixed $value): bool
    {
        if (! $this->user) {
            return false;
        }

        return Str::lower((string) $value) === Str::lower($this->user->email);
    }
}
