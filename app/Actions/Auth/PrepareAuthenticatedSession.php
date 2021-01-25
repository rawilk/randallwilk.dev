<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Services\Auth\LoginRateLimiter;

final class PrepareAuthenticatedSession
{
    private LoginRateLimiter $limiter;

    public function __construct(LoginRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Prepare a new session for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $request->session()->regenerate();

        $this->limiter->clear($request);

        return $next($request);
    }
}
