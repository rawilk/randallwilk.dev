<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Users;

use App\Actions\Users\UpdateProfileInformationAction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

final class UserDetailsForm extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public User $user;
    public array $state = [];
    public $photo;

    public function mount(): void
    {
        $this->state = [
            'name' => $this->user->name->full,
            'email' => $this->user->email,
            'timezone' => $this->user->timezone,
        ];
    }

    public function updatedPhoto($value): void
    {
        if (! $value) {
            return;
        }

        $this->validateOnly('photo', [
            'photo' => ['image', 'max:1024'],
        ]);
    }

    public function cancelUpload(): void
    {
        $this->photo = null;
        $this->resetErrorBag('photo');
    }

    public function save(UpdateProfileInformationAction $action)
    {
        $this->authorize('edit', $this->user);

        $this->resetErrorBag();

        $action->execute(
            $this->user,
            $this->photo
                    ? array_merge($this->state, ['photo' => $this->photo])
                    : $this->state
        );

        if (isset($this->photo)) {
            return redirect()->to($this->user->edit_url);
        }

        $this->emit('profile.updated');

        if ($this->user->is(Auth::user())) {
            $this->emit('refresh-navigation-dropdown');
        }
    }

    public function deleteProfilePhoto(): void
    {
        $this->authorize('edit', $this->user);

        $this->user->deleteAvatar();

        if ($this->user->is(Auth::user())) {
            $this->emit('refresh-profile-navigation');
        }
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit.user-details-form');
    }
}
