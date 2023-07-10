<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Exports\Users\UsersExport;
use App\Models\Access\Role;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Rawilk\LaravelBase\Contracts\Profile\DeletesUsers;
use Rawilk\LaravelBase\Http\Livewire\DataTable\HasDataTable;
use Rawilk\LaravelBase\Http\Livewire\DataTable\WithCachedRows;
use Rawilk\LaravelBase\Http\Livewire\DataTable\WithHighlighting;

final class Index extends Component
{
    use AuthorizesRequests;
    use HasDataTable;
    use WithCachedRows;
    use WithHighlighting;

    private const SELECTABLE_COLUMNS = [
        'id', 'h_key', 'first_name', 'last_name', 'email',
        'timezone', 'created_at', 'updated_at', 'avatar_path',
    ];

    public bool $showDelete = false;

    public bool $showBulkDelete = false;

    public ?User $deleting = null;

    public $roles = [];

    protected $listeners = [
        'imports.finished' => '$refresh',
    ];

    public array $filters = [
        'search' => '',
        'updated-min' => '',
        'updated-max' => '',
        'created-min' => '',
        'created-max' => '',
        'min-id' => '',
        'max-id' => '',
        'roles' => [],
    ];

    public function getRowsQueryProperty()
    {
        $query = User::query()
            ->with('roles:name')
            ->when($this->filters['search'], fn ($query, $search) => $query->modelSearch(['first_name', 'last_name', 'email'], $search))
            ->when($this->filters['min-id'], fn ($query, $id) => $query->where('id', '>=', $id))
            ->when($this->filters['max-id'], fn ($query, $id) => $query->where('id', '<=', $id))
            ->when($this->filters['created-min'], fn ($query, $date) => $query->where('created_at', '>=', $this->localizeMinDate($date)))
            ->when($this->filters['created-max'], fn ($query, $date) => $query->where('created_at', '<=', $this->localizeMaxDate($date)))
            ->when($this->filters['updated-min'], fn ($query, $date) => $query->where('updated_at', '>=', $this->localizeMinDate($date)))
            ->when($this->filters['updated-max'], fn ($query, $date) => $query->where('updated_at', '<=', $this->localizeMaxDate($date)))
            ->when($this->filters['roles'], fn ($query, $roles) => $query->whereHas('roles', fn ($q) => $q->whereIn('id', $roles)))
            ->select(self::SELECTABLE_COLUMNS);

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(fn () => $this->applyPagination($this->rowsQuery));
    }

    public function confirmDelete(User $user): void
    {
        $this->useCachedRows();

        $this->deleting = $user;
        $this->showDelete = true;
    }

    public function deleteUser(DeletesUsers $deleter): void
    {
        if (! $this->deleting) {
            return;
        }

        $this->authorize('delete', $this->deleting);

        $deleter->delete($this->deleting);

        $this->notify(__('base::users.alerts.deleted', ['name' => $this->deleting->name->full]));

        $this->deleting = null;
        $this->showDelete = false;
    }

    public function deleteSelected(DeletesUsers $deleter): void
    {
        $count = 0;

        $this->selectedRowsQuery
            ->clone()
            ->cursor()
            ->each(function (User $user) use (&$count, $deleter) {
                if (! Auth::user()->can('delete', $user)) {
                    return;
                }

                $deleter->delete($user);

                $count++;
            });

        $this->notify(
            Lang::choice('base::users.alerts.bulk_deleted', $count, ['count' => $count])
        );

        $this->deleting = null;
        $this->showBulkDelete = false;
    }

    public function exportSelected(): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->useCachedRows();

        return (new UsersExport($this->hasSelection ? $this->selectedRowsQuery->clone() : $this->rowsQuery->clone()))
            ->usingColumns(array_merge(self::SELECTABLE_COLUMNS, ['roles', 'permissions']))
            ->download('users_' . time() . '.csv');
    }

    public function sortName($query, $direction): void
    {
        $query->orderByRaw("concat(users.first_name, users.last_name) {$direction}");
    }

    private function mapFilterValue($key, $value)
    {
        if ($key === 'roles') {
            return array_map(function ($roleId) {
                return $this->roles->where('id', $roleId)->first()?->name;
            }, $value);
        }

        return $value;
    }

    public function mount(): void
    {
        $this->hideableColumns = [
            'id' => __('ID'),
            'name' => __('Name'),
            'email' => __('Email'),
            'roles' => __('Roles'),
            'timezone' => __('Timezone'),
            'created_at' => __('Created'),
            'updated_at' => __('Last Updated'),
        ];

        $this->hidden = ['id', 'timezone', 'created_at', 'updated_at'];

        $this->roles = Role::orderBy('name')->get(['id', 'name']);
    }

    public function render(): View
    {
        return view('livewire.admin.users.index.index', [
            'users' => $this->rows,
        ]);
    }
}
