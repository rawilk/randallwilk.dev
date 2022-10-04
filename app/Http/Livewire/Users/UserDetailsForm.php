<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelBase\Contracts\Profile\UpdatesUserProfileInformation;

final class UserDetailsForm extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public User $user;

    public array $state = [];

    public $photo;

    public function updatedPhoto($value): void
    {
        if (! $value) {
            return;
        }

        $this->validateOnly('photo', [
            'photo' => [
                'nullable',
                File::image()->max(1024),
            ],
        ]);
    }

    public function cancelUpload(): void
    {
        $this->photo = null;
        $this->resetValidation('photo');
    }

    public function save(UpdatesUserProfileInformation $updater)
    {
        $this->authorize('edit', $this->user);

        $this->resetValidation();

        $updater->update(
            $this->user,
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state,
        );

        if (isset($this->photo)) {
            // Photo has to be reset, otherwise flash message will be lost on redirect...
            $this->photo = null;

            return redirect()->to($this->user->edit_url)
                ->with(Alert::SUCCESS, __('Profile information updated successfully.'));
        }

        $this->emit('profile.updated');

        if ($this->user->is(Auth::user())) {
            $this->emit('refresh-navigation-menu');
        }
    }

    public function deleteProfilePhoto(): void
    {
        $this->authorize('edit', $this->user);

        $this->user->deleteAvatar();
        $this->emitSelf('profile.updated');

        if ($this->user->is(Auth::user())) {
            $this->emit('refresh-navigation-menu');
        }
    }

    public function mount(): void
    {
        $this->state = [
            'email' => $this->user->email,
            'timezone' => $this->user->timezone,
            'name' => $this->user->name->full,
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit.user-details-form');
    }
}
