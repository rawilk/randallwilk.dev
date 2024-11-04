<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\UploadedFile;
use Tests\Fixtures\Factories\Users\CreateUserDataFactory;

use function Pest\Laravel\be;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    be($this->user = adminUser());

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->requestData = CreateUserDataFactory::new();
});

it('renders', function () {
    get(UserResource::getUrl('create'))->assertOk();
});

it('creates a new user', function () {
    livewire(UserResource\Pages\CreateUser::class)
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
});

it('requires a name', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm($this->requestData->create(['name' => null]))
        ->call('create')
        ->assertHasFormErrors([
            'name' => ['required'],
        ]);
});

it('requires an email', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm($this->requestData->create(['email' => null]))
        ->call('create')
        ->assertHasFormErrors([
            'email' => ['required'],
        ]);
});

it('requires a valid email', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm($this->requestData->create(['email' => 'invalid']))
        ->call('create')
        ->assertHasFormErrors([
            'email' => ['email'],
        ]);
});

it('requires a unique email', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm($this->requestData->create(['email' => $this->user->email]))
        ->call('create')
        ->assertHasFormErrors([
            'email' => ['unique'],
        ]);
});

it('requires a timezone', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm($this->requestData->create(['timezone' => null]))
        ->call('create')
        ->assertHasFormErrors([
            'timezone' => ['required'],
        ]);
});

it('can create other admin users', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm($data = $this->requestData->create(['is_admin' => true]))
        ->call('create');

    $this->assertDatabaseHas(User::class, [
        'email' => $data['email'],
        'is_admin' => true,
    ]);
});

it('can create a user with an avatar picture', function () {
    $file = UploadedFile::fake()->image('avatar.png', 500);
    $data = $this->requestData->create([
        'avatar_path' => $file,
    ]);

    Storage::fake('avatars');

    FileUpload::configureUsing(function (FileUpload $component) {
        $component->preserveFilenames();
    });

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'email' => $data['email'],
        'avatar_path' => 'avatar.png',
    ]);

    expect(Storage::disk('avatars')->exists('avatar.png'))->toBeTrue();
});
