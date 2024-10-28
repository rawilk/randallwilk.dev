<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use App\Models\User;
use SensitiveParameter;

class LoginEventBag
{
    protected ?User $user = null;

    protected bool $hasCheckedUserSystemAccess = false;

    public function __construct(#[SensitiveParameter] public array $data)
    {
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function user(): ?User
    {
        return $this->user ?? auth()->user();
    }

    public function userSystemAccessCheckWasPerformed(): static
    {
        $this->hasCheckedUserSystemAccess = true;

        return $this;
    }

    public function shouldCheckForUserSystemAccess(): bool
    {
        return ! $this->hasCheckedUserSystemAccess;
    }
}
