<?php

namespace App\Events\Users;

use App\Models\User\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UserWasDeleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public User $user)
    {
    }
}
