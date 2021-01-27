<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Users;

use App\Http\Livewire\DataTable\HasDataTable;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithHighlighting;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

final class Index extends Component
{
    use HasDataTable, WithCachedRows, WithHighlighting;
    use AuthorizesRequests;

    public bool $showDelete = false;
    public bool $showDeleteAll = false;
    public null | User $deleting = null;

    protected $listeners = [
        'refresh-users' => '$refresh',
    ];

    public array $filters = [
        'search' => '',
        'updated-min' => '',
        'updated-max' => '',
        'created-min' => '',
        'created-max' => '',
        'min-id' => '',
        'max-id' => '',
        'is-admin' => '',
    ];

    public function confirmDelete(User $user): void
    {
        $this->useCachedRows();

        $this->deleting = $user;

        $this->showDelete = true;
    }

    public function deleteUser(): void
    {
        if (! $this->deleting) {
            return;
        }

        $this->authorize('delete', $this->deleting);

        $this->deleting->delete();

        $this->notify(__('users.alerts.deleted', ['name' => $this->deleting->name->full]));

        $this->showDelete = false;
    }

    public function deleteSelected(): void
    {
        // Each user will be authorized to be deleted in the user's "deleting" observer event.
        $ids = $this->selectedRowsQuery->pluck('id');

        $deleteCount = User::destroy($ids);

        $this->showDeleteAll = false;

        $this->notify(Lang::choice('users.alerts.bulk_deleted', $deleteCount, ['count' => $deleteCount]));
    }

    public function impersonate(User $user): void
    {
        $this->authorize('impersonate', $user);

        $user->impersonate();
    }

    public function getRowsQueryProperty()
    {
        $query = User::query()
            ->when($this->filters['search'], fn ($query, $search) => $query->search(['name', 'email'], $search))
            ->when($this->filters['is-admin'] === '0', fn ($query) => $query->where('is_admin', false))
            ->when($this->filters['is-admin'] === '1', fn ($query) => $query->where('is_admin', true))
            ->when($this->filters['min-id'], fn ($query, $id) => $query->where('id', '>=', $id))
            ->when($this->filters['max-id'], fn ($query, $id) => $query->where('id', '<=', $id))
            ->when($this->filters['created-min'], fn ($query, $date) => $query->where('created_at', '>=', $this->localizeMinDate($date)))
            ->when($this->filters['created-max'], fn ($query, $date) => $query->where('created_at', '<=', $this->localizeMaxDate($date)))
            ->when($this->filters['updated-min'], fn ($query, $date) => $query->where('updated_at', '>=', $this->localizeMinDate($date)))
            ->when($this->filters['updated-max'], fn ($query, $date) => $query->where('updated_at', '<=', $this->localizeMaxDate($date)));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(fn () => $this->applyPagination($this->rowsQuery));
    }

    public function getVisibleColumnCountProperty(): int
    {
        return (2 + count($this->hideableColumns)) - count($this->hidden);
    }

    public function showDropdownFor(User $user): bool
    {
        return Auth::user()->can('edit', $user)
            || Auth::user()->can('delete', $user);
    }

    public function mount(): void
    {
        $this->hideableColumns = [
            'id' => __('models.labels.id'),
            'name' => __('users.labels.name'),
            'email' => __('users.labels.email'),
            'timezone' => __('users.labels.timezone'),
            'is_admin' => __('users.labels.is_admin'),
            'created_at' => __('models.labels.created_at'),
            'updated_at' => __('models.labels.updated_at'),
        ];

        $this->hidden = ['id', 'timezone', 'created_at', 'updated_at'];
    }

    public function render(): View
    {
        return view('livewire.admin.users.index.index', [
            'users' => $this->rows,
        ])->layout('layouts.admin.base', ['title' => __('users.page_title')]);
    }
}
