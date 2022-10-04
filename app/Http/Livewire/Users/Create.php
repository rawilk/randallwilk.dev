<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Actions\Users\CreateUserAction;
use App\Http\Livewire\Users\Concerns\SavesUserAbilities;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Rawilk\LaravelBase\Components\Alerts\Alert;

/**
 * @property-read \App\Models\User\User $user
 */
final class Create extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;
    use SavesUserAbilities;

    public $photo;

    public array $state = [
        'name' => '',
        'email' => '',
        'timezone' => '',
    ];

    public string $password = '';

    public function getUserProperty(): User
    {
        return new User;
    }

    public function updatedPhoto($value): void
    {
        if (! $value) {
            return;
        }

        $this->validateOnly('photo', [
            'photo' => [
                File::image()->max(1024),
            ],
        ]);
    }

    public function store(CreateUserAction $creator): void
    {
        $this->authorize('create', User::class);

        $this->resetErrorBag();

        $creator(array_merge([
            'photo' => $this->photo,
            'password' => $this->password,
            'permissions' => $this->userPermissions,
            'roles' => $this->userRoles,
        ], $this->state));

        Session::flash(Alert::SUCCESS, __('User account was created successfully.'));

        redirect()->route('admin.users.index');
    }

    public function cancelUpload(): void
    {
        $this->photo = null;
        $this->resetErrorBag('photo');
    }

    public function mount(): void
    {
        $this->state['timezone'] = appTimezone();
    }

    public function render(): View
    {
        return view('livewire.admin.users.create.index');
    }
}
