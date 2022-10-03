<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Rawilk\LaravelBase\Contracts\Profile\UpdatesUserPasswords;

final class UpdatePasswordForm extends Component
{
    use AuthorizesRequests;

    public array $state = [
        'current_password' => '', // The authenticated user's password (for security)
        'password' => '',
    ];

    public User $user;

    public function updatePassword(UpdatesUserPasswords $updater): void
    {
        $this->authorize('edit', $this->user);

        $this->resetValidation();

        $updater->update($this->user, $this->state, true);

        $this->resetExcept('user');

        $this->emitSelf('password.updated');
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit.update-password-form');
    }
}
