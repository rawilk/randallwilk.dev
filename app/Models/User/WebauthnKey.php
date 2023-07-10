<?php

declare(strict_types=1);

namespace App\Models\User;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Rawilk\Webauthn\Models\WebauthnKey as BaseWebauthnKey;

class WebauthnKey extends BaseWebauthnKey
{
    use HasUuids;
}
