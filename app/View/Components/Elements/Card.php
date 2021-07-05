<?php

declare(strict_types=1);

namespace App\View\Components\Elements;

use App\View\Components\BladeComponent;

class Card extends BladeComponent
{
    public const TYPE_ERROR = 'error';
    public const TYPE_SUCCESS = 'success';

    public function __construct(
        public bool $flush = false, // Set to true to remove padding from the body
        public $header = false,
        public $footer = false,
        public string $headerClass = '',
        public string $type = '',
    ) {}

    public function getHeaderClass(): string
    {
        return collect([
            $this->headerClass,
            $this->headerColorClasses(),
        ])->filter()->implode(' ');
    }

    private function headerColorClasses(): string
    {
        return match($this->type) {
            self::TYPE_ERROR => 'bg-red-300 text-red-800',
            self::TYPE_SUCCESS => 'bg-green-300 text-green-800',
            default => 'bg-blue-gray-50',
        };
    }
}
