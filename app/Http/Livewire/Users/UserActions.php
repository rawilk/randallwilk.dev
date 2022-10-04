<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelBase\Contracts\Profile\DeletesUsers;

final class UserActions extends Component
{
    use AuthorizesRequests;

    public User $user;

    public bool $showDelete = false;

    protected $listeners = [
        'profile.updated' => '$refresh',
    ];

    public function confirmDelete(): void
    {
        $this->showDelete = true;
    }

    public function deleteUser(DeletesUsers $deleter): void
    {
        $this->authorize('delete', $this->user);

        $deleter->delete($this->user);

        Session::flash(Alert::SUCCESS, __(':name was deleted successfully!', ['name' => $this->user->name->full]));

        redirect(route('admin.users.index'));
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit.user-actions');
    }
}
