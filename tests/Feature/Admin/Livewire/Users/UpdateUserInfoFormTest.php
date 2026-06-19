<?php

declare(strict_types=1);

use App\Livewire\Users\UpdateUserInfoForm;
use App\Enums\Disk;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Rawilk\ProfileFilament\Models\PendingUserEmail;
use Rawilk\ProfileFilament\Notifications\Emails\NoticeOfEmailChangeRequest;
use Rawilk\ProfileFilament\Notifications\Emails\VerifyEmailChange;
use Tests\TestSupport\Factories\Users\UpdateUserDataFactory;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\be;
use function Pest\Livewire\livewire;

beforeEach(function () {
    be($this->user = adminUser());

    $this->otherUser = User::factory()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->formData = UpdateUserDataFactory::new();

    $this->component = UpdateUserInfoForm::class;
});

it('renders', function () {
    livewire($this->component, [
        'user' => $this->otherUser,
    ])
        ->assertOk();
});

it('populates the form with the users info', function () {
    livewire($this->component, [
        'user' => $this->otherUser,
    ])
        ->assertFormSet([
            'name' => $this->otherUser->name,
            'email' => $this->otherUser->email,
            'timezone' => $this->otherUser->timezone,
            'is_admin' => $this->otherUser->is_admin,
        ]);
});

it('disables the is admin field for the authenticated user', function () {
    livewire($this->component, [
        'user' => $this->user,
    ])
        ->assertFormSet([
            'is_admin' => true,
        ])
        ->assertFormFieldIsDisabled('is_admin');
});

it('disables the email field for the authenticated user', function () {
    livewire($this->component, [
        'user' => $this->user,
    ])
        ->assertFormSet([
            'email' => $this->user->email,
        ])
        ->assertFormFieldIsDisabled('email');
});

it('updates a users info', function () {
    Notification::fake();

    livewire($this->component, [
        'user' => $this->otherUser,
    ])
        ->fillForm($data = $this->formData->create())
        ->call('update')
        ->assertHasNoFormErrors();

    expect($this->otherUser->refresh())
        ->name->full->toBe($data['name'])
        ->timezone->toBe($data['timezone'])
        ->is_admin->toBe($data['is_admin'])
        // New emails require verification.
        ->email->not->toBe($data['email']);

    assertDatabaseHas(PendingUserEmail::class, [
        'email' => $data['email'],
        'user_id' => $this->otherUser->getKey(),
    ]);

    Notification::assertSentTo($this->otherUser, NoticeOfEmailChangeRequest::class); // block verification email
    Notification::assertSentOnDemand(VerifyEmailChange::class);
});

it('requires sudo mode to cancel a pending email change', function () {
    PendingUserEmail::factory()->create([
        'user_type' => $this->otherUser->getMorphClass(),
        'user_id' => $this->otherUser->getKey(),
    ]);

    livewire($this->component, [
        'user' => $this->otherUser,
    ])
        ->mountFormComponentAction('email', 'cancelPendingEmailChange')
        ->assertActionMounted('sudoChallenge');

    assertDatabaseHas(PendingUserEmail::class, [
        'user_id' => $this->otherUser->getKey(),
    ]);
});

it('can cancel a pending email change with sudo mode active', function () {
    Sudo::activate();

    PendingUserEmail::factory()->create([
        'user_type' => $this->otherUser->getMorphClass(),
        'user_id' => $this->otherUser->getKey(),
    ]);

    livewire($this->component, [
        'user' => $this->otherUser,
    ])
        ->callFormComponentAction('email', 'cancelPendingEmailChange');

    assertDatabaseMissing(PendingUserEmail::class, [
        'user_id' => $this->otherUser->getKey(),
    ]);
});

describe('validation', function () {
    it('requires a name', function () {
        livewire($this->component, [
            'user' => $this->otherUser,
        ])
            ->fillForm($this->formData->create(['name' => '']))
            ->call('update')
            ->assertHasFormErrors([
                'name' => ['required'],
            ]);
    });

    it('requires a name under 255 characters', function () {
        livewire($this->component, [
            'user' => $this->otherUser,
        ])
            ->fillForm($this->formData->create(['name' => str_repeat('a', 256)]))
            ->call('update')
            ->assertHasFormErrors([
                'name' => ['max'],
            ]);
    });

    it('requires an email', function () {
        livewire($this->component, [
            'user' => $this->otherUser,
        ])
            ->fillForm($this->formData->create(['email' => '']))
            ->call('update')
            ->assertHasFormErrors([
                'email' => ['required'],
            ]);
    });

    it('requires a valid email', function () {
        livewire($this->component, [
            'user' => $this->otherUser,
        ])
            ->fillForm($this->formData->create(['email' => 'invalid']))
            ->call('update')
            ->assertHasFormErrors([
                'email' => ['email'],
            ]);
    });

    it('requires a valid production email', function (string $email) {
        putAppInProduction();

        livewire($this->component, [
            'user' => $this->otherUser,
        ])
            ->fillForm($this->formData->create(['email' => $email]))
            ->call('update')
            ->assertHasFormErrors([
                'email' => ['email'],
            ]);
    })->with('invalid production emails');

    it('requires an email under 255 characters', function () {
        livewire($this->component, [
            'user' => $this->otherUser,
        ])
            ->fillForm($this->formData->create(['email' => str_repeat('a', 256) . '@example.com']))
            ->call('update')
            ->assertHasFormErrors([
                'email' => ['max'],
            ]);
    });

    it('requires a unique email', function () {
        livewire($this->component, [
            'user' => $this->otherUser,
        ])
            ->fillForm($this->formData->create(['email' => $this->user->email]))
            ->call('update')
            ->assertHasFormErrors(['email']);
    });

    it('requires a timezone', function () {
        livewire($this->component, [
            'user' => $this->otherUser,
        ])
            ->fillForm($this->formData->create(['timezone' => '']))
            ->call('update')
            ->assertHasFormErrors([
                'timezone' => ['required'],
            ]);
    });
});

it('ignores email when it does not change', function () {
    Notification::fake();

    livewire($this->component, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create(['email' => $this->otherUser->email]))
        ->call('update')
        ->assertHasNoFormErrors();

    Notification::assertNothingSent();
});

it('can update the users avatar', function () {
    Notification::fake();

    Disk::Avatars->fake();

    $file = UploadedFile::fake()->image('avatar.png', 500, 500);

    FileUpload::configureUsing(function (FileUpload $component) {
        $component->preserveFilenames();
    });

    livewire($this->component, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create([
            'avatar_path' => $file,
        ]))
        ->call('update')
        ->assertHasNoFormErrors();

    expect($this->otherUser->refresh())->avatar_path->toBe('avatar.png');
});
