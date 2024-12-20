<?php

declare(strict_types=1);

namespace App\Responses\Auth;

use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class TwoFactorRedirectResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        return redirect()->route(
            filament()->getCurrentPanel()->generateRouteName('auth.mfa.challenge'),
        );
    }
}
