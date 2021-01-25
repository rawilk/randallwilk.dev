<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

final class UserActions extends Component
{
    use AuthorizesRequests;

    public User $user;
    public bool $showDelete = false;

    protected $listeners = [
        'profile.updated' => '$refresh',
    ];

    public function impersonate(): void
    {
        $this->authorize('impersonate', $this->user);

        $this->user->impersonate();
    }

    public function confirmDelete(): void
    {
        $this->showDelete = true;
    }

    public function deleteUser(): void
    {
        $this->authorize('delete', $this->user);

        $this->user->delete();

        Session::flash('success', __('users.alerts.deleted', ['name' => $this->user->name->full]));

        redirect(route('admin.users'));
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit.user-actions');
    }
}
