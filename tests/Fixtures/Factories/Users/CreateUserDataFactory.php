<?php

declare(strict_types=1);

namespace Tests\Fixtures\Factories\Users;

class CreateUserDataFactory
{
    protected string $email = 'email@example.com';

    protected string $name = 'Dexter Morgan';

    protected string $timezone = 'America/Chicago';

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
