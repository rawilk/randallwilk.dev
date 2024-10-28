<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\ProfileFilament\Models\WebauthnKey as BaseWebauthnKey;

class WebauthnKey extends BaseWebauthnKey
{
    public static function getUserHandle(User $user): string
    {
        return $user->getRouteKey();
    }
}
