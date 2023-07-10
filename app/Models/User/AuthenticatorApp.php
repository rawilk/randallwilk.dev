<?php

declare(strict_types=1);

namespace App\Models\User;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Rawilk\LaravelBase\Models\AuthenticatorApp as BaseAuthenticatorApp;

class AuthenticatorApp extends BaseAuthenticatorApp
{
    use HasUuids;
}
