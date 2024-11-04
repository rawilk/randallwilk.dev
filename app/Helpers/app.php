<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\User;
use Illuminate\Contracts\Support\Htmlable;
use Rawilk\ProfileFilament\Support\Agent;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

function appTimezone(): string
{
    return config('randallwilk.timezone', 'UTC');
}

function userTimezone(?User $user = null): string
{
    $user ??= auth()->user();

    return $user?->timezone ?? appTimezone();
}

function formatPageTitle(string ...$segments): string
{
    return collect($segments)
        ->flatten()
        ->implode(' - ');
}

function defaultEmailSalutation(): Htmlable
{
    return str(__('branding.notifications.salutation'))->inlineMarkdown()->toHtmlString();
}

function getUserPositionFromIp(?string $ip = null): ?Position
{
    $ip ??= request()?->ip();

    if (
        (! $ip || $ip === '127.0.0.1') &&
        (! app()->runningUnitTests())
    ) {
        $ip = config('location.testing.ip');
    }

    return rescue(
        callback: fn () => cache()->remember(
            key: "ip-location:{$ip}",
            ttl: now()->addMonth(),
            callback: fn () => Location::get($ip),
        ),
    );
}

function userLocationFromIp(?string $ip = null): ?string
{
    $position = getUserPositionFromIp($ip);

    if (! $position) {
        return null;
    }

    $country = $position->countryName ?? $position->countryCode;

    return collect([
        $position->cityName,
        $position->regionName,
        $country,
    ])->filter()->implode(', ');
}

function currentBrowserName(): string|bool
{
    return (new Agent)->platform() ?? false;
}

function currentPlatformName(): string|bool
{
    return (new Agent)->browser() ?? false;
}
