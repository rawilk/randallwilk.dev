<?php

namespace App\Http\Livewire\DataTable;

use Illuminate\Support\Str;

trait WithSorting
{
    public array $sorts = [];

    public function sortBy($field)
    {
        if (! isset($this->sorts[$field])) {
            return $this->sorts[$field] = 'asc';
        }

        if ($this->sorts[$field] === 'asc') {
            return $this->sorts[$field] = 'desc';
        }

        unset($this->sorts[$field]);
    }

    public function applySorting($query)
    {
        foreach ($this->sorts as $field => $direction) {
            $method = 'sort' . Str::studly($field);

            if (method_exists($this, $method)) {
                $this->$method($query, $direction);
            } else {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }
}
