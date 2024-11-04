<?php

declare(strict_types=1);

use App\Livewire\Users\UpdatePasswordForm;
use App\Models\User;
use Rawilk\ProfileFilament\Events\UserPasswordWasUpdated;

use function Pest\Laravel\be;
use function Pest\Livewire\livewire;

beforeEach(function () {
    be($this->user = adminUser());

    $this->otherUser = User::factory()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    livewire(UpdatePasswordForm::class, [
        'user' => $this->otherUser,
    ])
        ->assertOk();
});

it('can update the users password', function () {
    Sudo::activate();

    Event::fake();

    livewire(UpdatePasswordForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm([
            'password' => $password = Str::password(),
        ])
        ->call('update')
        ->assertHasNoFormErrors()
        ->assertFormSet([
            'password' => null,
        ]);

    expect($password)->toBePasswordFor($this->otherUser->refresh());

    Event::assertDispatched(UserPasswordWasUpdated::class, function (UserPasswordWasUpdated $event) {
        expect($event->user)->toBe($this->otherUser);

        return true;
    });
});

it('requires sudo mode to update the users password', function () {
    Event::fake();

    livewire(UpdatePasswordForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm([
            'password' => $password = Str::password(),
        ])
        ->call('update')
        ->assertDispatched('check-sudo');

    expect($password)->not->toBePasswordFor($this->otherUser->refresh());

    Event::assertNotDispatched(UserPasswordWasUpdated::class);
});

it('requires a password', function () {
    Sudo::activate();

    livewire(UpdatePasswordForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm([
            'password' => null,
        ])
        ->call('update')
        ->assertHasFormErrors([
            'password' => ['required'],
        ]);
});
