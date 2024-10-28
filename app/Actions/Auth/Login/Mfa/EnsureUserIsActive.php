<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Mfa;

use App\Enums\SessionAlert;
use Closure;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Rawilk\ProfileFilament\Dto\Auth\TwoFactorLoginEventBag;

class EnsureUserIsActive
{
    public function __invoke(TwoFactorLoginEventBag $request, Closure $next)
    {
        // TODO: Handle suspended users.

        return $next($request);
    }

    protected function sendFailedResponse(string $error): RedirectResponse|Redirector
    {
        SessionAlert::Error->flash($error);

        return redirect()->to(filament()->getLoginUrl());
    }
}
