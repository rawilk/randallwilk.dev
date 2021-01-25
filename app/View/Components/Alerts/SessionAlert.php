<?php

declare(strict_types=1);

namespace App\View\Components\Alerts;

use App\View\Components\BladeComponent;
use Illuminate\Support\Arr;

class SessionAlert extends BladeComponent
{
    public string $type;

    public function __construct(string $type = 'alert')
    {
        $this->type = $type;
    }

    public function message(): string
    {
        return (string) Arr::first($this->messages());
    }

    public function messages(): array
    {
        return (array) session()->get($this->type);
    }

    public function exists(): bool
    {
        return session()->has($this->type) && ! empty($this->messages());
    }
}
