<?php

namespace App\Http\Livewire\DataTable;

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

trait WithPerPagePagination
{
    use WithPagination;

    public int $perPage = 10;

    protected function perPageSessionKey(): string
    {
        return 'perPage';
    }

    public function initializeWithPerPagePagination(): void
    {
        $this->perPage = Session::get($this->perPageSessionKey(), $this->perPage);
    }

    public function updatedPerPage($value): void
    {
        Session::put($this->perPageSessionKey(), $value);

        $this->resetPage();
    }

    public function applyPagination($query)
    {
        return $query->paginate($this->perPage);
    }
}
