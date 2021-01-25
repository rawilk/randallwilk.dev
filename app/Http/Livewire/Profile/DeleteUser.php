<?php

declare(strict_types=1);

namespace App\Http\Livewire\Profile;

use App\Actions\Users\DeleteUserAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class DeleteUser extends Component
{
    public bool $confirmingUserDeletion = false;
    public string $deleteUserPassword = '';

    public function confirmUserDeletion(): void
    {
        $this->resetErrorBag();

        $this->deleteUserPassword = '';

        $this->dispatchBrowserEvent('confirming-delete-user');

        $this->confirmingUserDeletion = true;
    }

    public function deleteUser(DeleteUserAction $action)
    {
        $this->resetErrorBag();

        $this->validate([
            'deleteUserPassword' => ['required', 'password'],
        ], [
            'required' => __('Please enter your password.'),
            'password' => __('This password does not match our records.'),
        ]);

        $user = clone Auth::user();

        Auth::logout();

        $action->execute($user);

        return redirect()->route('home');
    }
}
