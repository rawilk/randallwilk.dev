<?php

declare(strict_types=1);

use App\Livewire\Users\UpdateUserInfoForm;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\UploadedFile;
use Rawilk\ProfileFilament\Mail\PendingEmailVerificationMail;
use Rawilk\ProfileFilament\Models\PendingUserEmail;
use Tests\Fixtures\Factories\Users\UpdateUserDataFactory;

use function Pest\Laravel\be;
use function Pest\Livewire\livewire;

beforeEach(function () {
    be($this->user = adminUser());

    $this->otherUser = User::factory()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->formData = UpdateUserDataFactory::new();
});

it('renders', function () {
    livewire(UpdateUserInfoForm::class, [
        'user' => $this->otherUser,
    ])
        ->assertOk();
});

it('populates the form with the users info', function () {
    livewire(UpdateUserInfoForm::class, [
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
    livewire(UpdateUserInfoForm::class, [
        'user' => $this->user,
    ])
        ->assertFormSet([
            'is_admin' => true,
        ])
        ->assertFormFieldIsDisabled('is_admin');
});

it('updates a users info', function () {
    Mail::fake();

    livewire(UpdateUserInfoForm::class, [
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

    $this->assertDatabaseHas(PendingUserEmail::class, [
        'email' => $data['email'],
        'user_id' => $this->otherUser->getKey(),
    ]);

    Mail::assertQueued(function (PendingEmailVerificationMail $mail) use ($data) {
        expect($mail->panelId)->toBe('admin')
            ->and($mail->pendingUserEmail->email)->toBe($data['email']);

        $mail->assertTo($data['email']);

        return true;
    });
});

it('requires a name', function () {
    livewire(UpdateUserInfoForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create(['name' => null]))
        ->call('update')
        ->assertHasFormErrors([
            'name' => ['required'],
        ]);
});

it('requires an email', function () {
    livewire(UpdateUserInfoForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create(['email' => null]))
        ->call('update')
        ->assertHasFormErrors([
            'email' => ['required'],
        ]);
});

it('requires a valid email', function () {
    livewire(UpdateUserInfoForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create(['email' => 'invalid']))
        ->call('update')
        ->assertHasFormErrors([
            'email' => ['email'],
        ]);
});

it('requires a unique email', function () {
    livewire(UpdateUserInfoForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create(['email' => $this->user->email]))
        ->call('update')
        ->assertHasFormErrors([
            'email' => ['unique'],
        ]);
});

it('does ignores email when it does not change', function () {
    Mail::fake();

    livewire(UpdateUserInfoForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create(['email' => $this->otherUser->email]))
        ->call('update')
        ->assertHasNoFormErrors();

    Mail::assertNothingOutgoing();
});

it('requires a timezone', function () {
    livewire(UpdateUserInfoForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create(['timezone' => null]))
        ->call('update')
        ->assertHasFormErrors([
            'timezone' => ['required'],
        ]);
});

it('can update the users avatar', function () {
    Storage::fake('avatars');

    $file = UploadedFile::fake()->image('avatar.png', 500);

    FileUpload::configureUsing(function (FileUpload $component) {
        $component->preserveFilenames();
    });

    livewire(UpdateUserInfoForm::class, [
        'user' => $this->otherUser,
    ])
        ->fillForm($this->formData->create([
            'avatar_path' => $file,
        ]))
        ->call('update')
        ->assertHasNoFormErrors();

    expect($this->otherUser->refresh())->avatar_path->toBe('avatar.png');
});
