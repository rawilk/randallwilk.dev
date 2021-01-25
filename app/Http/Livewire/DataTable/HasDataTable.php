<?php

namespace App\Http\Livewire\DataTable;

trait HasDataTable
{
    use WithPerPagePagination,
        WithSorting,
        WithBulkActions,
        HidesColumns,
        WithFiltering;
}
