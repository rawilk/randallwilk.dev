<?php

declare(strict_types=1);

use App\Enums\RepositoryType;
use App\Filament\Admin\Resources\RepositoryResource;
use App\Models\Repository;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Illuminate\Bus\PendingBatch;
use Tests\Fixtures\Factories\Repositories\UpdateRepositoryDataFactory;

use function Pest\Laravel\be;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    be(adminUser());

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->formData = UpdateRepositoryDataFactory::new();
});

it('renders', function () {
    get(RepositoryResource::getUrl())
        ->assertOk();
});

it('lists repositories', function () {
    $records = Repository::factory()->count(5)->create();
    $trashed = Repository::factory()->trashed()->count(2)->create();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->assertCanSeeTableRecords($records)
        ->assertCanNotSeeTableRecords($trashed)
        ->set('activeTab', 'trashed')
        ->assertCanNotSeeTableRecords($records)
        ->assertCanSeeTableRecords($trashed);
});

it('has an action to view a repository', function () {
    $record = Repository::factory()->create();

    $expectedUrl = RepositoryResource::getUrl('view', [
        'record' => $record,
    ]);

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->assertTableActionHasUrl(
            name: 'view',
            url: $expectedUrl,
            record: $record,
        );
});

it('can delete a repository', function () {
    $this->freezeSecond();

    $records = Repository::factory()->count(5)->create();
    $record = $records->first();

    $component = livewire(RepositoryResource\Pages\ListRepositories::class)
        ->assertTableActionVisible('delete', $record)
        ->callTableAction('delete', $record)
        ->assertCanNotSeeTableRecords([$record]);

    $record = Repository::withTrashed()->find($record->getKey());

    $component
        ->set('activeTab', 'trashed')
        ->assertCanSeeTableRecords([$record])
        ->assertTableActionHidden('delete', $record);

    expect($record->deleted_at)->toBe(now());
});

it('can restore a repository', function () {
    $record = Repository::factory()->trashed()->create();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->set('activeTab', 'trashed')
        ->assertTableActionVisible('restore', $record)
        ->callTableAction('restore', $record)
        ->set('activeTab', 'not-trashed')
        ->assertCanSeeTableRecords([$record->refresh()])
        ->assertTableActionHidden('restore', $record);

    expect($record->deleted_at)->toBeNull();
});

it('can edit a repository', function () {
    $record = Repository::factory()->create();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->mountTableAction('edit', $record)
        ->assertTableActionDataSet([
            'type' => $record->type,
            'scoped_name' => $record->scoped_name,
            'documentation_url' => $record->documentation_url,
            'blogpost_url' => $record->blogpost_url,
            'visible' => $record->isVisible(),
            'highlighted' => $record->highlighted,
            'new' => $record->new,
        ])
        ->callTableAction('edit', $record, data: $data = $this->formData->create(['scoped_name' => 'foo']))
        ->assertHasNoTableActionErrors();

    expect($record->refresh())
        ->type->value->toBe($data['type'])
        ->scoped_name->toBe('foo')
        ->isVisible()->toBe($data['visible']);
});

it('requires a type', function () {
    $record = Repository::factory()->create();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->callTableAction('edit', $record, data: $this->formData->create(['type' => null]))
        ->assertHasTableActionErrors([
            'type' => ['required'],
        ]);
});

it('requires a unique scoped name', function () {
    $record = Repository::factory()->create();
    Repository::factory()->create(['scoped_name' => 'foo']);

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->callTableAction('edit', $record, data: $this->formData->create(['scoped_name' => 'foo']))
        ->assertHasTableActionErrors([
            'scoped_name' => ['unique'],
        ]);
});

it('is searchable', function () {
    $records = Repository::factory()->count(5)->create();
    $record = $records->first();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->searchTable($record->name)
        ->assertCanSeeTableRecords([$record])
        ->assertCanNotSeeTableRecords($records->filter(fn (Repository $other) => $other->isNot($record)));
});

it('has an action to sync all repositories', function () {
    Bus::fake();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->assertTableActionExists('sync')
        ->callTableAction('sync');

    Bus::assertBatched(function (PendingBatch $batch) {
        expect($batch->name)->toBe('manual_repo_sync:all')
            ->and($batch->jobs[0])->toHaveCount(3);

        return true;
    });
});

it('has an action to import all docs', function () {
    Bus::fake();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->assertTableActionExists('importDocs')
        ->callTableAction('importDocs');

    Bus::assertBatched(function (PendingBatch $batch) {
        $repositoriesWithDocs = count(config('docs.repositories'));

        expect($batch->name)->toBe('manual_docs_import:all')
            ->and($batch->jobs[0])->toHaveCount($repositoriesWithDocs + 2);

        return true;
    });
});

it('can bulk edit repositories', function () {
    $records = Repository::factory()->visible()->package()->count(2)->create();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->callTableBulkAction('editBulk', $records, data: [
            'type_status' => 'edit',
            'type' => RepositoryType::Project->value,
            'visible_status' => 'edit',
            'visible' => false,
        ])
        ->assertHasNoTableBulkActionErrors();

    foreach ($records as $record) {
        expect($record->refresh())
            ->type->toBe(RepositoryType::Project)
            ->isVisible()->toBeFalse();
    }
});

it('can soft delete multiple repositories', function () {
    $records = Repository::factory()->count(2)->create();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->callTableBulkAction(DeleteBulkAction::class, $records)
        ->assertCanNotSeeTableRecords($records)
        ->set('activeTab', 'trashed')
        ->assertCanSeeTableRecords($records)
        ->assertTableBulkActionHidden(DeleteBulkAction::class);
});

it('can restore multiple repositories', function () {
    $records = Repository::factory()->trashed()->count(2)->create();

    livewire(RepositoryResource\Pages\ListRepositories::class)
        ->assertTableBulkActionHidden(RestoreBulkAction::class)
        ->set('activeTab', 'trashed')
        ->callTableBulkAction(RestoreBulkAction::class, $records)
        ->assertCanNotSeeTableRecords($records)
        ->set('activeTab', 'not-trashed')
        ->assertCanSeeTableRecords($records);
});
