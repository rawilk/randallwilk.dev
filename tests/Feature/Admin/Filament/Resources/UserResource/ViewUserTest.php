<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\UserResource;
use App\Livewire\Users\UpdatePasswordForm;
use App\Livewire\Users\UpdateUserInfoForm;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs($this->user = adminUser());

    $this->otherUser = User::factory()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    get(UserResource::getUrl('view', ['record' => $this->otherUser]))
        ->assertOk()
        ->assertSeeLivewire(UpdateUserInfoForm::class)
        ->assertSeeLivewire(UpdatePasswordForm::class);
});

it('shows possible actions for the user', function () {
    livewire(UserResource\Pages\ViewUser::class, [
        'record' => $this->otherUser->getRouteKey(),
    ])
        ->assertInfolistActionVisible('delete', 'delete');
});

it('hides the delete action for the authenticated user', function () {
    livewire(UserResource\Pages\ViewUser::class, [
        'record' => $this->user->getRouteKey(),
    ])
        ->assertDontSeeText(__('users/view.sections.actions.heading'));
});

it('can delete a user', function () {
    livewire(UserResource\Pages\ViewUser::class, [
        'record' => $this->otherUser->getRouteKey(),
    ])
        ->callInfolistAction('delete', 'delete')
        ->assertRedirect(UserResource::getUrl());

    $this->assertModelMissing($this->otherUser);
});
