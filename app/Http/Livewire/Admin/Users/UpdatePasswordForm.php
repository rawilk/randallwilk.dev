<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Users;

use App\Actions\Profile\UpdatePasswordAction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

final class UpdatePasswordForm extends Component
{
    use AuthorizesRequests;

    public User $user;

    public array $state = [
        'current_password' => '', // The authenticated user's password (for security)
        'password' => '',
    ];

    public function updatePassword(UpdatePasswordAction $action): void
    {
        $this->authorize('edit', $this->user);

        $this->resetErrorBag();

        $action->execute($this->user, $this->state, true);

        $this->reset('state');

        $this->emit('password.updated');
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit.update-password-form');
    }
}
