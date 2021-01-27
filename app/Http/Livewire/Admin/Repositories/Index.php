<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Repositories;

use App\Console\Commands\Github\ImportGithubIssuesCommand;
use App\Console\Commands\Github\ImportGithubRepositoriesCommand;
use App\Console\Commands\Github\ImportPackagistDownloadsCommand;
use App\Console\Commands\Npm\ImportNpmDownloadsCommand;
use App\Http\Livewire\DataTable\WithFiltering;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Jobs\ImportDocsJob;
use App\Models\Repository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Livewire\Component;

final class Index extends Component
{
    use AuthorizesRequests;
    use WithPerPagePagination, WithFiltering;

    public bool $showDelete = false;
    public bool $showEdit = false;
    public null | Repository $deleting = null;
    public null | Repository $editing = null;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    public $filters = [
        'search' => '',
        'visible' => '',
        'type' => '',
        'new' => '',
        'highlighted' => '',
        'docs' => '',
        'blogpost' => '',
    ];

    public function rules(): array
    {
        return [
            'editing.visible' => ['sometimes', 'boolean'],
            'editing.type' => ['required', Rule::in(array_keys(Repository::TYPES))],
            'editing.documentation_url' => ['nullable', 'string', 'max:255'],
            'editing.blogpost_url' => ['nullable', 'string', 'max:255'],
            'editing.new' => ['sometimes', 'boolean'],
            'editing.highlighted' => ['sometimes', 'boolean'],
        ];
    }

    public function confirmDelete(Repository $repository): void
    {
        $this->deleting = $repository;

        $this->showDelete = true;
    }

    public function deleteRepository(): void
    {
        if (! $this->deleting) {
            return;
        }

        $this->authorize('delete', $this->deleting);

        $this->deleting->delete();

        $this->notify(__(':name was deleted!', ['name' => $this->deleting->name]));

        $this->showDelete = false;
    }

    public function toggleVisible(Repository $repository): void
    {
        $this->authorize('edit', $repository);

        $repository->visible = ! $repository->visible;
        $repository->save();

        if ($this->editing && $this->editing->is($repository)) {
            $this->editing->visible = $repository->visible;
        }
    }

    public function edit(Repository $repository): void
    {
        if (! $this->editing || $this->editing->isNot($repository)) {
            $this->editing = $repository;
            $this->resetErrorBag();

            if (is_null($this->editing->type)) {
                $this->editing->type = '';
            }
        }

        $this->showEdit = true;
    }

    public function save(): void
    {
        if (! $this->editing) {
            return;
        }

        $this->authorize('edit', $this->editing);

        $this->validate();

        $this->editing->save();

        $this->notify(__(':name was updated!', ['name' => $this->editing->name]));

        $this->showEdit = false;
    }

    public function syncRepos(): void
    {
        $commands = [
            ImportGithubRepositoriesCommand::class,
            ImportGithubIssuesCommand::class,
            ImportPackagistDownloadsCommand::class,
            ImportNpmDownloadsCommand::class,
        ];

        foreach ($commands as $command) {
            Artisan::call($command);
        }

        $this->notify(__('Repositories were synced!'));
    }

    public function syncDocs(): void
    {
        $repositoryNames = collect(Config::get('docs.repositories'))->keyBy('repository')->keys()->toArray();

        /*
         * Dispatching a job for each of them individually because for some reason the job doesn't import them all
         * if done in one batch...
         *
         * Also, this should really only be called on a fresh install of the app...
         */
        foreach ($repositoryNames as $name) {
            ImportDocsJob::dispatch([$name]);
        }

        $this->notify(__('Docs have been queued to sync!'));
    }

    public function getRowsQueryProperty()
    {
        $query = Repository::query()
            ->when($this->filters['search'], fn ($query, $search) => $query->search(['name', 'description'], $search))
            ->when($this->filters['type'], fn ($query, $type) => $query->where('type', $type))
            ->when(! blank($this->filters['visible']), fn ($query) => $query->where('visible', (int) $this->filters['visible'] === 1))
            ->when(! blank($this->filters['new']), fn ($query) => $query->where('new', (int) $this->filters['new'] === 1))
            ->when(! blank($this->filters['highlighted']), fn ($query) => $query->where('highlighted', (int) $this->filters['highlighted'] === 1))
            ->when((int) $this->filters['docs'] === 1, fn ($query) => $query->whereNotNull('documentation_url'))
            ->when(! blank($this->filters['docs']) && (int) $this->filters['docs'] === 0, fn ($query) => $query->whereNull('documentation_url'))
            ->when((int) $this->filters['blogpost'] === 1, fn ($query) => $query->whereNotNull('blogpost_url'))
            ->when(! blank($this->filters['blogpost']) && (int) $this->filters['blogpost'] === 0, fn ($query) => $query->whereNull('blogpost_url'));

        return $query->orderBy($this->sortBy, $this->sortDirection);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
    }

    public function render()
    {
        return view('livewire.admin.repositories.index.index', [
            'repositories' => $this->rows,
        ])->layout('layouts.admin.base', ['title' => __('repositories.page_title')]);
    }
}
