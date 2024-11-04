<?php

declare(strict_types=1);

namespace Tests\Fixtures\Factories\Users;

class UpdateUserDataFactory
{
    protected string $name = 'New name';

    protected string $email = 'new@example.test';

    protected string $timezone = 'Europe/London';

    protected bool $isAdmin = false;

    public static function new(): static
    {
        return new static;
    }

    public function create(array $extra = []): array
    {
        return $extra + [
            'name' => $this->name,
            'email' => $this->email,
            'timezone' => $this->timezone,
            'is_admin' => $this->isAdmin,
        ];
    }
}
