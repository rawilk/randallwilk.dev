<?php

declare(strict_types=1);

namespace App\Notifications\Concerns;

use App\Models\User;
use App\Notifications\Users\AccountSecurityNotification;

use function App\Helpers\currentBrowserName;
use function App\Helpers\currentPlatformName;
use function App\Helpers\userLocationFromIp;

trait SetsDeviceDetails
{
    protected function setDeviceDetailsOn(AccountSecurityNotification $notification, ?User $user = null): void
    {
        $notification
            ->fromLocation(userLocationFromIp())
            ->fromIp(request()?->ip())
            ->onDate(now()->inUserTimezone($user))
            ->usingBrowser(currentBrowserName())
            ->onPlatform(currentPlatformName());
    }
}
