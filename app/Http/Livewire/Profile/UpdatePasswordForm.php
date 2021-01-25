<?php

declare(strict_types=1);

namespace App\Http\Livewire\Profile;

use App\Actions\Profile\UpdatePasswordAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class UpdatePasswordForm extends Component
{
    public array $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    public function updatePassword(UpdatePasswordAction $action): void
    {
        $this->resetErrorBag();

        $action->execute(Auth::user(), $this->state);

        $this->reset('state');

        $this->emit('password.updated');
    }
}
