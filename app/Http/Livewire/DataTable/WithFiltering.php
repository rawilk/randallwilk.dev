<?php

namespace App\Http\Livewire\DataTable;

use Carbon\Carbon;

trait WithFiltering
{
    public bool $showFilters = false;

    public function resetFilters(): void
    {
        $this->reset('filters');
    }

    public function toggleShowFilters(): void
    {
        $this->showFilters = ! $this->showFilters;

        if (! $this->showFilters) {
            $this->emit('filters-hidden');
        }
    }

    public function updatedFilters(): void
    {
        $this->resetPage();
    }

    protected function localizeMinDate($date): Carbon
    {
        return Carbon::parse($date, userTimezone())->startOfDay()->utc();
    }

    protected function localizeMaxDate($date): Carbon
    {
        return Carbon::parse($date, userTimezone())->endOfDay()->utc();
    }
}
