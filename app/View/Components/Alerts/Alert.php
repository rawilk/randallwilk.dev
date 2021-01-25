<?php

declare(strict_types=1);

namespace App\View\Components\Alerts;

use App\View\Components\BladeComponent;

class Alert extends BladeComponent
{
    public string $type;
    public bool $icon;
    public bool $dismiss;
    public bool $border;
    public string $title;

    public function __construct(
        string $type = 'info',
        bool $icon = true,
        bool $dismiss = false,
        bool $border = true,
        string $title = ''
    ) {
        $this->type = $type;
        $this->icon = $icon;
        $this->dismiss = $dismiss;
        $this->border = $border;
        $this->title = $title;
    }

    public function iconComponent(): string
    {
        switch ($this->type) {
            case 'error':
                return 'heroicon-s-x-circle';
            case 'success':
                return 'heroicon-s-check-circle';
            case 'warning':
                return 'heroicon-s-exclamation';
            default:
            case 'info':
                return 'heroicon-s-information-circle';
        }
    }
}
