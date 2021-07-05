<?php

declare(strict_types=1);

namespace App\View\Components\Table;

use App\View\Components\BladeComponent;
use Illuminate\Support\Str;

final class Table extends BladeComponent
{
    public function __construct(
        public null|string $id = null,
        public bool $border = false,
        public $head = null,
        public null|string $tbodyRef = null,
        public bool $rounded = true,
    )
    {
        $this->id = $this->id ?? Str::random(10);
    }

    public function tableId(): string
    {
        return "table-{$this->id}";
    }

    public function tableClass($extraClasses = null): string
    {
        return collect(array_filter([
            'table',
            'min-w-full',
            'divide-y',
            'divide-blue-gray-200',
            $extraClasses,
        ]))->filter()->implode(' ');
    }
}
