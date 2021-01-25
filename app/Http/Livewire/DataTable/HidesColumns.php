<?php

namespace App\Http\Livewire\DataTable;

trait HidesColumns
{
    public $hidden = [];
    public array $hideableColumns = [];

    public function toggleColumn(string $column): void
    {
        if ($this->isHidden($column)) {
            $this->unhideColumn($column);
        } else {
            $this->hidden[] = $column;
        }
    }

    public function unhideColumn(string $column): void
    {
        if (($key = array_search($column, $this->hidden, true)) !== false) {
            unset($this->hidden[$key]);
        }
    }

    public function isHidden(string $column): bool
    {
        return in_array($column, $this->hidden, true);
    }
}
