<?php

namespace App\Events\Users;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UserPasswordWasUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public User $user, public bool $passwordUpdatedBySelf = true)
    {
    }
}
