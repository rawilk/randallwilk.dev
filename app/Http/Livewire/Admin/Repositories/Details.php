<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Repositories;

use App\Console\Commands\Github\ImportGithubIssuesCommand;
use App\Console\Commands\Github\ImportGithubRepositoriesCommand;
use App\Console\Commands\Github\ImportPackagistDownloadsCommand;
use App\Console\Commands\Npm\ImportNpmDownloadsCommand;
use App\Jobs\ImportDocsJob;
use App\Models\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

final class Details extends Component
{
    use AuthorizesRequests;

    public Repository $repository;

    public array $state = [];
    public bool $showEdit = false;
    public bool $showDelete = false;

    public function rules(): array
    {
        return [
            'visible' => ['sometimes', 'boolean'],
            'type' => ['required', Rule::in(array_keys(Repository::TYPES))],
            'documentation_url' => ['nullable', 'string', 'max:255'],
            'blogpost_url' => ['nullable', 'string', 'max:255'],
            'new' => ['sometimes', 'boolean'],
            'highlighted' => ['sometimes', 'boolean'],
        ];
    }

    public function edit(): void
    {
        if (! count($this->state)) {
            $this->state = $this->repository->toArray();
        }

        $this->showEdit = true;
    }

    public function save(): void
    {
        $this->authorize('edit', $this->repository);

        $this->resetErrorBag();
        $data = Validator::make($this->state, $this->rules())->validate();

        $this->repository->forceFill($data)->save();

        $this->notify(__('Repository was updated!'));

        $this->showEdit = false;
    }

    public function confirmDelete(): void
    {
        $this->showDelete = true;
    }

    public function deleteRepository(): void
    {
        $this->authorize('delete', $this->repository);

        $this->repository->delete();

        redirect()->route('admin.repositories');
    }

    public function updateInfo(): void
    {
        $this->authorize('edit', $this->repository);

        $commands = [
            ImportGithubRepositoriesCommand::class,
            ImportGithubIssuesCommand::class,
        ];

        if ($this->repository->type === 'package') {
            $commands[] = $this->repository->isNpmPackage() ? ImportNpmDownloadsCommand::class : ImportPackagistDownloadsCommand::class;
        }

        foreach ($commands as $command) {
            Artisan::call($command, ['--repo' => $this->repository->name]);
        }

        $this->repository->refresh();

        $this->notify(__('Repository details were synced!'));
    }

    public function syncDocs(): void
    {
        $this->authorize('edit', $this->repository);

        if (! $this->repository->hasDocs()) {
            return;
        }

        ImportDocsJob::dispatch([$this->repository->full_name]);

        $this->notify(__('Repository docs are queued to sync.'));
    }

    public function render(): View
    {
        return view('livewire.admin.repositories.edit.index');
    }
}
