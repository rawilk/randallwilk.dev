<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Repositories;

use App\Actions\Repositories\DeleteRepositoryAction;
use App\Actions\Repositories\UpdateRepositoryAction;
use App\Jobs\Docs\CleanupDocsImportJob;
use App\Jobs\Docs\CleanupRepositoryFoldersJob;
use App\Jobs\Repositories\ImportDocsFromRepositoryJob;
use App\Jobs\Repositories\ImportNpmDownloadsJob;
use App\Jobs\Repositories\ImportPackagistDownloadsJob;
use App\Jobs\Repositories\ImportRepositoriesJob;
use App\Models\Repository;
use App\Notifications\Repositories\ManualDocSyncFinished;
use App\Notifications\Repositories\ManualSyncFinished;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Rawilk\LaravelBase\Components\Alerts\Alert;

final class Show extends Component
{
    use AuthorizesRequests;

    public Repository $repository;

    public array $state = [];

    public bool $showEdit = false;

    public bool $showDelete = false;

    public function confirmDelete(): void
    {
        $this->showDelete = true;
    }

    public function deleteRepository(DeleteRepositoryAction $deleter)
    {
        $this->authorize('delete', $this->repository);

        $deleter($this->repository);

        Session::flash(Alert::SUCCESS, __('repos.alerts.deleted', ['name' => $this->repository->name]));

        redirect()->route('admin.repositories.index');
    }

    public function edit(): void
    {
        if (! count($this->state)) {
            $this->state = $this->repository->withoutRelations()->toArray();
        }

        $this->showEdit = true;
    }

    public function save(UpdateRepositoryAction $updater): void
    {
        $this->authorize('edit', $this->repository);

        $updater($this->repository, $this->state);

        $this->notify(__('repos.alerts.updated', ['name' => $this->repository->name]));

        $this->showEdit = false;
        $this->state = [];
    }

    public function syncInfo(): void
    {
        $this->authorize('edit', $this->repository);

        $jobs = array_filter([
            new ImportRepositoriesJob(config('services.github.username'), $this->repository->name),
            $this->getDownloadsImportJob(),
        ]);

        $user = Auth::user()->withoutRelations();
        Bus::batch($jobs)->finally(function (Batch $batch) use ($user) {
            $user->notify(new ManualSyncFinished($batch->id, $batch->name));
        })->name("manual_repo_sync:{$this->repository->name}")->dispatch();

        $this->notify(__('repos.alerts.repo_synced'));
    }

    public function syncDocs(): void
    {
        $this->authorize('edit', $this->repository);

        if (! $repositoryConfig = $this->getRepositoryConfig()) {
            $this->notify(__('repos.alerts.doc_config_not_found'), Alert::ERROR);

            return;
        }

        $jobs = [
            new CleanupRepositoryFoldersJob,
            new ImportDocsFromRepositoryJob($repositoryConfig),
        ];

        $user = Auth::user()->withoutRelations();
        $repository = $this->repository->withoutRelations();
        Bus::batch([$jobs])
            ->finally(function (Batch $batch) use ($user, $repository) {
                CleanupDocsImportJob::dispatch();

                $user->notify(new ManualDocSyncFinished(
                    $batch->id,
                    $batch->name,
                    $repository,
                ));
            })
            ->name("manual_doc_sync:{$this->repository->full_name}")
            ->dispatch();

        $this->notify(__('repos.alerts.docs_synced'));
    }

    public function render(): View
    {
        // Tell any dropdowns to close.
        $this->dispatchBrowserEvent('modal-shown');

        return view('livewire.admin.repositories.show.index');
    }

    private function getDownloadsImportJob(): null|ImportPackagistDownloadsJob|ImportNpmDownloadsJob
    {
        if (! $this->repository->isPackage()) {
            return null;
        }

        if ($this->repository->isNpmPackage()) {
            return new ImportNpmDownloadsJob($this->repository->nameForNpm());
        }

        return new ImportPackagistDownloadsJob(config('services.github.username'), $this->repository->name);
    }

    private function getRepositoryConfig(): ?array
    {
        $repositoriesWithDocs = collect(config('docs.repositories'))->keyBy('repository');

        return $repositoriesWithDocs[$this->repository->full_name] ?? null;
    }
}
