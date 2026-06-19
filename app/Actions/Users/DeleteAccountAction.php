<?php

declare(strict_types=1);

namespace App\Actions\Users;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\ProfileFilament\Contracts\DeleteAccountAction as DeleteAccountContract;
use Rawilk\ProfileFilament\Events\UserDeletedAccount;

readonly class DeleteAccountAction implements DeleteAccountContract
{
    use Concerns\DeletesUsers;

    public function __invoke(User $user): void
    {
        /** @noinspection PhpParamsInspection */
        $this->deleteUser($user);

        UserDeletedAccount::dispatch($user);
    }
}
