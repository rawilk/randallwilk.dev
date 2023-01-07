<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Repositories;

use App\Actions\Repositories\DeleteRepositoryAction;
use App\Actions\Repositories\ToggleVisibilityAction;
use App\Actions\Repositories\UpdateRepositoryAction;
use App\Enums\PermissionEnum;
use App\Jobs\Docs\CleanupDocsImportJob;
use App\Jobs\Docs\CleanupRepositoryFoldersJob;
use App\Jobs\Repositories\ImportDocsFromRepositoryJob;
use App\Jobs\Repositories\ImportNpmDownloadsJob;
use App\Jobs\Repositories\ImportPackagistDownloadsJob;
use App\Jobs\Repositories\ImportRepositoriesJob;
use App\Models\GitHub\Repository;
use App\Notifications\Repositories\ManualDocSyncFinished;
use App\Notifications\Repositories\ManualSyncFinished;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Livewire\Component;
use Rawilk\LaravelBase\Http\Livewire\DataTable\HasDataTable;
use Rawilk\LaravelBase\Http\Livewire\DataTable\WithCachedRows;

final class Index extends Component
{
    use AuthorizesRequests;
    use HasDataTable;
    use WithCachedRows;

    public bool $showDelete = false;

    public bool $showEdit = false;

    public array $state = [];

    public ?Repository $deleting = null;

    public ?Repository $editing = null;

    public array $filters = [
        'search' => '',
        'visible' => '',
        'type' => '',
        'new' => '',
        'highlighted' => '',
        'docs' => '',
        'blogpost' => '',
    ];

    public function getRowsQueryProperty()
    {
        $query = Repository::query()
            ->when($this->filters['search'], fn ($query, $search) => $query->modelSearch(['name', 'description', 'scoped_name'], $search))
            ->byType($this->filters['type'])
            ->when(! blank($this->filters['visible']), fn ($query) => $query->where('visible', (int) $this->filters['visible'] === 1))
            ->when(! blank($this->filters['new']), fn ($query) => $query->where('new', (int) $this->filters['new'] === 1))
            ->when(! blank($this->filters['highlighted']), fn ($query) => $query->where('highlighted', (int) $this->filters['highlighted'] === 1))
            ->when(! blank($this->filters['docs']), function ($query) {
                $queryMethod = (int) $this->filters['docs'] === 1 ? 'whereNotNull' : 'whereNull';

                $query->{$queryMethod}('documentation_url');
            })
            ->when(! blank($this->filters['blogpost']), function ($query) {
                $queryMethod = (int) $this->filters['blogpost'] === 1 ? 'whereNotNull' : 'whereNull';

                $query->{$queryMethod}('blogpost_url');
            });

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(fn () => $this->applyPagination($this->rowsQuery));
    }

    public function confirmDelete(Repository $repository): void
    {
        $this->deleting = $repository;
        $this->showDelete = true;
    }

    public function deleteRepository(DeleteRepositoryAction $deleter): void
    {
        if (! $this->deleting) {
            return;
        }

        $this->authorize('delete', $this->deleting);

        $deleter($this->deleting);

        $this->notify(__('repos.alerts.deleted', ['name' => $this->deleting->name]));

        if ($this->deleting->is($this->editing)) {
            $this->editing = null;
        }

        $this->deleting = null;
        $this->showDelete = false;
    }

    public function edit(Repository $repository): void
    {
        if ($repository->isNot($this->editing)) {
            $this->editing = $repository;
            $this->state = $repository->withoutRelations()->toArray();
        }

        $this->showEdit = true;
    }

    public function save(UpdateRepositoryAction $updater): void
    {
        if (! $this->editing) {
            return;
        }

        $this->authorize('edit', $this->editing);

        $updater($this->editing, $this->state);

        $this->notify(__('repos.alerts.updated', ['name' => $this->editing->name]));

        $this->showEdit = false;
    }

    public function toggleVisible(Repository $repository, ToggleVisibilityAction $toggler): void
    {
        $this->authorize('edit', $repository);

        $toggler($repository);

        if ($repository->is($this->editing)) {
            $this->state['visible'] = $repository->visible;
        }
    }

    public function syncRepos(): void
    {
        abort_unless(Auth::user()->can(PermissionEnum::REPOSITORIES_MANAGE->value), Response::HTTP_FORBIDDEN);

        $username = config('services.github.username');
        $user = Auth::user()->withoutRelations();
        Bus::batch([
            new ImportRepositoriesJob($username),
            new ImportPackagistDownloadsJob($username),
            new ImportNpmDownloadsJob,
        ])->finally(function (Batch $batch) use ($user) {
            $user->notify(new ManualSyncFinished($batch->id, $batch->name));
        })->name('manual_repo_sync:all')->dispatch();

        $this->notify(__('repos.alerts.repos_synced'));
    }

    public function syncDocs(): void
    {
        abort_unless(Auth::user()->can(PermissionEnum::REPOSITORIES_MANAGE->value), Response::HTTP_FORBIDDEN);

        $jobs = [
            new CleanupRepositoryFoldersJob,
            ...$this->convertRepositoriesToDocImportJobs(),
        ];

        $user = Auth::user()->withoutRelations();
        Bus::batch([$jobs])
            ->finally(function (Batch $batch) use ($user) {
                CleanupDocsImportJob::dispatch();

                $user->notify(new ManualDocSyncFinished($batch->id, $batch->name));
            })
            ->name('manual_doc_sync:all')
            ->dispatch();

        $this->notify(__('repos.alerts.docs_synced'));
    }

    public function render(): View
    {
        // Tell any dropdowns to close
        $this->dispatchBrowserEvent('modal-shown');

        return view('livewire.admin.repositories.index.index', [
            'repositories' => $this->rows,
        ]);
    }

    private function mapFilterValue($key, $value)
    {
        return match ($value) {
            '1', 1 => 'yes',
            '0', 0 => 'no',
            default => $value,
        };
    }

    private function convertRepositoriesToDocImportJobs(): array
    {
        return $this->getRepositoriesWithDocs()
            ->map(fn (array $repository) => new ImportDocsFromRepositoryJob($repository))
            ->toArray();
    }

    private function getRepositoriesWithDocs(): Collection
    {
        return collect(config('docs.repositories'))->keyBy('repository');
    }
}
