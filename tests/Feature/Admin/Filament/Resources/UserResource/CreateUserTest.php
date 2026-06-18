<?php

declare(strict_types=1);

use App\Enums\Disk;
use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;
use App\Notifications\Users\WelcomeNotification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\UploadedFile;
use Tests\TestSupport\Factories\Users\CreateUserDataFactory;

use function Pest\Laravel\be;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    be($this->user = adminUser());

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->requestData = CreateUserDataFactory::new();

    $this->page = CreateUser::class;
});

it('renders', function () {
    get(UserResource::getUrl('create'))->assertOk();
});

it('creates a new user', function () {
    Notification::fake();

    livewire($this->page)
        ->fillForm($data = $this->requestData->create())
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertRedirect();

    $this->assertDatabaseHas(User::class, [
        'name' => $data['name'],
        'email' => $data['email'],
        'timezone' => $data['timezone'],
        'is_admin' => $data['is_admin'],
    ]);

    $user = User::where('email', $data['email'])->first();

    Notification::assertSentTo($user, WelcomeNotification::class);
});

describe('validation', function () {
    it('requires a name', function () {
        livewire($this->page)
            ->fillForm($this->requestData->create(['name' => '']))
            ->call('create')
            ->assertHasFormErrors([
                'name' => ['required'],
            ]);
    });

    it('requires a name under 255 characters', function () {
        livewire($this->page)
            ->fillForm($this->requestData->create(['name' => str_repeat('a', 256)]))
            ->call('create')
            ->assertHasFormErrors([
                'name' => ['max'],
            ]);
    });

    it('requires an email', function () {
        livewire($this->page)
            ->fillForm($this->requestData->create(['email' => '']))
            ->call('create')
            ->assertHasFormErrors([
                'email' => ['required'],
            ]);
    });

    it('requires a valid email', function () {
        livewire($this->page)
            ->fillForm($this->requestData->create(['email' => 'invalid']))
            ->call('create')
            ->assertHasFormErrors([
                'email' => ['email'],
            ]);
    });

    it('requires a valid production email', function (string $email) {
        putAppInProduction();

        livewire($this->page)
            ->fillForm($this->requestData->create(['email' => $email]))
            ->call('create')
            ->assertHasFormErrors(['email']);
    })->with('invalid production emails');

    it('requires an email under 255 characters', function () {
        livewire($this->page)
            ->fillForm($this->requestData->create(['email' => str_repeat('a', 256) . '@example.com']))
            ->call('create')
            ->assertHasFormErrors([
                'email' => ['max'],
            ]);
    });

    it('requires a unique email', function () {
        livewire($this->page)
            ->fillForm($this->requestData->create(['email' => $this->user->email]))
            ->call('create')
            ->assertHasFormErrors(['email']);
    });

    it('requires a timezone', function () {
        livewire($this->page)
            ->fillForm($this->requestData->create(['timezone' => '']))
            ->call('create')
            ->assertHasFormErrors([
                'timezone' => ['required'],
            ]);
    });
});

it('can create other admin users', function () {
    Notification::fake();

    livewire($this->page)
        ->fillForm($data = $this->requestData->create(['is_admin' => true]))
        ->call('create');

    $this->assertDatabaseHas(User::class, [
        'email' => $data['email'],
        'is_admin' => true,
    ]);
});

it('can create a user with an avatar picture', function () {
    Notification::fake();

    $file = UploadedFile::fake()->image('avatar.png', 500, 500);
    $data = $this->requestData->create([
        'avatar_path' => $file,
    ]);

    Disk::Avatars->fake();

    FileUpload::configureUsing(function (FileUpload $component) {
        $component->preserveFilenames();
    });

    livewire($this->page)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'email' => $data['email'],
        'avatar_path' => 'avatar.png',
    ]);

    expect(Disk::Avatars->toDisk()->exists('avatar.png'))->toBeTrue();
});
