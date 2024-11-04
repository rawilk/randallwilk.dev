<?php

declare(strict_types=1);

use App\Actions\Users\DeleteUserAction;
use App\Filament\Admin\Actions\Users\DeleteUserTableAction;
use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Tables\Actions\EditAction;

use function Pest\Laravel\be;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = adminUser();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    get(UserResource::getUrl())->assertOk();
});

it('lists the users', function () {
    $users = User::factory()->count(5)->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('is searchable', function () {
    $users = User::factory()->count(5)->create();

    $user1 = $users->first();
    $user2 = $users->last();

    livewire(UserResource\Pages\ListUsers::class)
        ->searchTable($user1->email)
        ->assertCanSeeTableRecords([$user1])
        ->assertCanNotSeeTableRecords($users->filter(fn (User $other) => $other->isNot($user1)))
        ->searchTable($user2->name->full)
        ->assertCanSeeTableRecords([$user2])
        ->assertCanNotSeeTableRecords($users->filter(fn (User $other) => $other->isNot($user2)));
});

it('does not show delete action for the authenticated user row', function () {
    $users = User::factory()->count(5)->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertTableActionDisabled(DeleteUserTableAction::class, record: $this->user)
        ->assertTableActionEnabled(DeleteUserTableAction::class, record: $users->first());
});

it('has an edit action', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertTableActionHasUrl(
            name: EditAction::class,
            url: UserResource::getUrl('view', ['record' => $user]),
            record: $user,
        );
});

it('deletes a user', function () {
    $user = User::factory()->create();

    $this->mock(DeleteUserAction::class)
        ->shouldReceive('__invoke')
        ->withArgs(fn ($param) => $user->is($param));

    livewire(UserResource\Pages\ListUsers::class)
        ->callTableAction(DeleteUserTableAction::class, record: $user);
});
