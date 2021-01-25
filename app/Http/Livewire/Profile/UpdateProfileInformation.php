<?php

declare(strict_types=1);

namespace App\Http\Livewire\Profile;

use App\Actions\Users\UpdateProfileInformationAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

final class UpdateProfileInformation extends Component
{
    use WithFileUploads;

    public array $state = [];
    public $photo;

    public function mount(): void
    {
        $this->state = Auth::user()->withoutRelations()->toArray();
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updatedPhoto(): void
    {
        $this->validateOnly('photo', [
            'photo' => ['image', 'max:1024'],
        ]);
    }

    public function updateProfileInformation(UpdateProfileInformationAction $action)
    {
        $this->resetErrorBag();

        $action->execute(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            return redirect()->route('profile.show');
        }

        $this->emit('profile.updated');

        $this->emit('refresh-profile-navigation');
    }

    public function deleteProfilePhoto(): void
    {
        Auth::user()->deleteAvatar();

        $this->emit('refresh-profile-navigation');
        $this->emit('profile.updated');
    }
}
