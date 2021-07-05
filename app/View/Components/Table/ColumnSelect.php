<?php

declare(strict_types=1);

namespace App\View\Components\Table;

use App\View\Components\BladeComponent;

final class ColumnSelect extends BladeComponent
{
    public function __construct(public array $columns, public array $hidden = []) {}

    public function isHidden(string $column): bool
    {
        return in_array($column, $this->hidden, true);
    }
}
