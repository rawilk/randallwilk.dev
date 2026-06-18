<?php

declare(strict_types=1);

use App\Filament\Admin\Actions\Users\DeleteUserAction;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\be;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = adminUser();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->page = ListUsers::class;
});

it('renders', function () {
    get(UserResource::getUrl())->assertOk();
});

it('lists the users', function () {
    $users = User::factory()->count(5)->create();

    livewire($this->page)
        ->assertCanSeeTableRecords($users);
});

it('is searchable', function () {
    $users = User::factory()->count(5)->create();

    $user1 = $users->first();
    $user2 = $users->last();

    livewire($this->page)
        ->searchTable($user1->email)
        ->assertCanSeeTableRecords([$user1])
        ->assertCanNotSeeTableRecords($users->filter(fn (User $other) => $other->isNot($user1)))
        ->searchTable($user2->name->full)
        ->assertCanSeeTableRecords([$user2])
        ->assertCanNotSeeTableRecords($users->filter(fn (User $other) => $other->isNot($user2)));
});

it('does not show delete action for the authenticated user row', function () {
    $users = User::factory()->count(5)->create();

    livewire($this->page)
        ->assertTableActionDisabled(DeleteUserAction::class, record: $this->user)
        ->assertTableActionEnabled(DeleteUserAction::class, record: $users->first());
});

it('has an edit action', function () {
    $user = User::factory()->create();

    livewire($this->page)
        ->assertActionExists(
            TestAction::make(EditAction::class)->table($user),
            function ($action) use ($user): bool {
                if ($action->getRecord()->isNot($user)) {
                    return true;
                }

                return $action->getUrl() === UserResource::getUrl('view', ['record' => $user]);
            },
        );
});

it('requires sudo mode to delete a user', function () {
    $user = User::factory()->create();

    livewire($this->page)
        ->mountTableAction(DeleteUserAction::class, record: $user)
        ->assertActionMounted('sudoChallenge');

    $this->assertModelExists($user);
});

it('deletes a user with sudo mode active', function () {
    Sudo::activate();

    $user = User::factory()->create();

    livewire($this->page)
        ->callTableAction(DeleteUserAction::class, record: $user);

    assertModelMissing($user);
});
