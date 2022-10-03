<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Actions\Users\UpdateAbilitiesAction;
use App\Http\Livewire\Users\Concerns\SavesUserAbilities;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class UserAbilitiesForm extends Component
{
    use AuthorizesRequests;
    use SavesUserAbilities;

    public User $user;

    public function updateAbilities(UpdateAbilitiesAction $updater): void
    {
        abort_unless(
            Auth::user()->canAny(['assignRolesTo', 'assignPermissionsTo'], $this->user),
            Response::HTTP_FORBIDDEN,
        );

        $updater($this->user, [
            'roles' => $this->userRoles,
            'permissions' => $this->userPermissions,
        ]);

        $this->emitSelf('abilities.updated');
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit.user-abilities-form');
    }
}
