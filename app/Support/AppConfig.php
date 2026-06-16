<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Uri;

class AppConfig
{
    public static function appDomain(): string
    {
        return Uri::of(config('app.url'))->host() ?? '';
    }

    public static function supportEmail(): string
    {
        return config('randallwilk.support_email');
    }

    public static function passwordResetDecayMinutes(): int
    {
        return (int) config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
    }

    public static function defaultReplyToEmail(): string
    {
        return config('randallwilk.contact.email');
    }

    public static function authTimeboxDuration(): int
    {
        return Config::integer('auth.timebox_duration', 200_000);
    }

    public static function gitHubToken(): string
    {
        return config('services.github.token');
    }

    public static function emailHashKey(): string
    {
        return config('randallwilk.secrets.email_hash_key');
    }

    public static function horizonEmail(): string
    {
        return config('services.horizon.email');
    }
}
