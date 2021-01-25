<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Users;

use App\Actions\Users\CreateUserAction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

final class Create extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public User $user;

    /*
     * The avatar for the new user.
     */
    public $photo;

    /*
     * The new user's password.
     */
    public null|string $password = null;

    public function rules(): array
    {
        // Just adding some rules to make livewire happy about binding to
        // model properties. Actual validation will happen in the
        // create user action.
        return [
            'user.name' => ['required'],
            'user.email' => ['required'],
            'user.timezone' => ['required'],
            'user.is_admin' => ['boolean'],
        ];
    }

    public function getAuthUserProperty()
    {
        return Auth::user();
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

    public function createUser(CreateUserAction $action): void
    {
        $this->authorize('create', User::class);

        $this->resetErrorBag();

        $action->execute(array_merge([
            'photo' => $this->photo,
            'password' => $this->password,
        ], $this->user->toArray(), ['name' => $this->user->name->full]));

        Session::flash(__('users.alerts.created'));

        redirect()->route('admin.users');
    }

    public function cancelUpload(): void
    {
        $this->photo = null;
        $this->resetErrorBag('photo');
    }

    public function mount(): void
    {
        $this->user = User::make([
            'timezone' => appTimezone(),
            'is_admin' => false,
        ]);
    }

    public function render(): View
    {
        return view('livewire.admin.users.create.index')
            ->layout('layouts.admin.base', ['title' => __('users.form.create_title')]);
    }
}
