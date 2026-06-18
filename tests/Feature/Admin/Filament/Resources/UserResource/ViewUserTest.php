<?php

declare(strict_types=1);

use App\Filament\Admin\Actions\Users\DeleteUserAction;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Livewire\Users\UpdateUserInfoForm;
use App\Models\User;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs($this->user = adminUser());

    $this->otherUser = User::factory()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->page = ViewUser::class;
});

it('renders', function () {
    get(UserResource::getUrl('view', ['record' => $this->otherUser]))
        ->assertOk()
        ->assertSeeLivewire(UpdateUserInfoForm::class);
});

it('shows possible actions for the user', function () {
    livewire($this->page, [
        'record' => $this->otherUser->getRouteKey(),
    ])
        ->assertActionVisible(TestAction::make(DeleteUserAction::class)->schemaComponent('user-actions', 'infolist'));
});

it('hides the delete action for the authenticated user', function () {
    livewire($this->page, [
        'record' => $this->user->getRouteKey(),
    ])
        ->assertDontSeeText(__('users/view.sections.actions.heading'));
});

it('requires sudo mode to delete a user', function () {
    livewire($this->page, [
        'record' => $this->otherUser->getRouteKey(),
    ])
        ->callAction(TestAction::make(DeleteUserAction::class)->schemaComponent('user-actions', 'infolist'))
        ->assertActionMounted('sudoChallenge');

    assertModelExists($this->otherUser);
});

it('can delete a user with sudo mode active', function () {
    Sudo::activate();

    livewire($this->page, [
        'record' => $this->otherUser->getRouteKey(),
    ])
        ->callAction(TestAction::make(DeleteUserAction::class)->schemaComponent('user-actions', 'infolist'))
        ->assertRedirect(UserResource::getUrl());

    assertModelMissing($this->otherUser);
});
