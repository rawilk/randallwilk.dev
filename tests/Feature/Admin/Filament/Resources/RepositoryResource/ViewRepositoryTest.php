<?php

declare(strict_types=1);

use App\Filament\Admin\Actions\Repositories\DeleteRepositoryAction;
use App\Filament\Admin\Resources\RepositoryResource;
use App\Models\Repository;
use Filament\Actions\RestoreAction;
use Illuminate\Bus\PendingBatch;
use Tests\Fixtures\Factories\Repositories\UpdateRepositoryDataFactory;

use function Pest\Laravel\be;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    be(adminUser());

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->formData = UpdateRepositoryDataFactory::new();

    $this->record = Repository::factory()->package()->create();
});

it('renders', function () {
    get(RepositoryResource::getUrl('view', ['record' => $this->record]))
        ->assertOk();
});

it('renders for soft-deleted records', function () {
    $record = Repository::factory()->trashed()->create();

    get(RepositoryResource::getUrl('view', ['record' => $record]))
        ->assertOk();
});

it('can delete the repository', function () {
    $this->freezeSecond();

    livewire(RepositoryResource\Pages\ViewRepository::class, [
        'record' => $this->record->getRouteKey(),
    ])
        ->callAction(DeleteRepositoryAction::class)
        ->assertSuccessful()
        ->assertActionHidden(DeleteRepositoryAction::class)
        ->assertActionVisible(RestoreAction::class);

    expect($this->record->refresh())->deleted_at->toBe(now());
});

it('can restore the repository', function () {
    $record = Repository::factory()->trashed()->create();

    livewire(RepositoryResource\Pages\ViewRepository::class, [
        'record' => $record->getRouteKey(),
    ])
        ->callAction(RestoreAction::class)
        ->assertSuccessful()
        ->assertActionHidden(RestoreAction::class)
        ->assertActionVisible(DeleteRepositoryAction::class);

    expect($record->refresh())->deleted_at->toBeNull();
});

it('can edit the repository', function () {
    livewire(RepositoryResource\Pages\ViewRepository::class, [
        'record' => $this->record->getRouteKey(),
    ])
        ->mountAction('edit')
        ->assertActionDataSet([
            'type' => $this->record->type,
            'scoped_name' => $this->record->scoped_name,
            'documentation_url' => $this->record->documentation_url,
            'blogpost_url' => $this->record->blogpost_url,
            'visible' => $this->record->isVisible(),
            'highlighted' => $this->record->highlighted,
            'new' => $this->record->new,
        ])
        ->callAction('edit', data: $data = $this->formData->create(['scoped_name' => 'foo']))
        ->assertHasNoActionErrors();

    expect($this->record->refresh())
        ->type->value->toBe($data['type'])
        ->scoped_name->toBe('foo')
        ->isVisible()->toBe($data['visible']);
});

it('requires a type', function () {
    livewire(RepositoryResource\Pages\ViewRepository::class, [
        'record' => $this->record->getRouteKey(),
    ])
        ->callAction('edit', data: $this->formData->create(['type' => null]))
        ->assertHasActionErrors([
            'type' => ['required'],
        ]);
});

it('requires a unique scoped name', function () {
    Repository::factory()->trashed()->create(['scoped_name' => 'taken']);

    livewire(RepositoryResource\Pages\ViewRepository::class, [
        'record' => $this->record->getRouteKey(),
    ])
        ->callAction('edit', data: $this->formData->create(['scoped_name' => 'taken']))
        ->assertHasActionErrors([
            'scoped_name' => ['unique'],
        ]);
});

it('has an action to sync repository info with GitHub', function () {
    Bus::fake();

    livewire(RepositoryResource\Pages\ViewRepository::class, [
        'record' => $this->record->getRouteKey(),
    ])
        ->assertActionExists('sync')
        ->callAction('sync');

    Bus::assertBatched(function (PendingBatch $batch) {
        expect($batch->name)->toBe("manual_repo_sync:{$this->record->name}")
            ->and($batch->jobs[0])->toHaveCount(2);

        return true;
    });
});

it('has an action to import the docs', function () {
    Bus::fake();

    config()->set('docs.repositories', [
        $this->record->name => [
            'name' => $this->record->name,
            'repository' => 'rawilk/' . $this->record->name,
            'branches' => [
                'main' => 'v1',
            ],
            'main_branch' => 'main',
            'category' => 'Laravel',
        ],
    ]);

    livewire(RepositoryResource\Pages\ViewRepository::class, [
        'record' => $this->record->getRouteKey(),
    ])
        ->assertActionVisible('importDocs')
        ->callAction('importDocs');

    Bus::assertBatched(function (PendingBatch $batch) {
        expect($batch->name)->toBe("manual_doc_import:{$this->record->full_name}")
            ->and($batch->jobs[0])->toHaveCount(3);

        return true;
    });
});

it('does not show the import docs action if the repository has no docs configured', function () {
    config()->set('docs.repositories', []);

    livewire(RepositoryResource\Pages\ViewRepository::class, [
        'record' => $this->record->getRouteKey(),
    ])
        ->assertActionHidden('importDocs');
});
