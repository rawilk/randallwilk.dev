<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Users;

use App\Actions\Users\UpdateAccountInfoAction;
use App\Http\Livewire\Concerns\ConfirmsPasswords;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

final class AccountInfoForm extends Component
{
    use AuthorizesRequests;
    use ConfirmsPasswords;

    public User $user;
    public array $state = [];

    public function updateAccount(UpdateAccountInfoAction $action): void
    {
        $this->authorize('edit', $this->user);

        $this->resetErrorBag();

        $action->execute($this->user, $this->state);

        $this->emit('account.updated');
    }

    public function mount(): void
    {
        $this->state = [
            'is_admin' => $this->user->is_admin,
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit.account-info-form');
    }
}
