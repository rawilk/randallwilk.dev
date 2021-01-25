<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Services\Auth\LoginRateLimiter;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;

final class AttemptToAuthenticate
{
    private StatefulGuard $guard;
    private LoginRateLimiter $limiter;

    public function __construct(StatefulGuard $guard, LoginRateLimiter $limiter)
    {
        $this->guard = $guard;
        $this->limiter = $limiter;
    }

    /**
     * Attempt to authenticate a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle($request, $next)
    {
        if ($this->guard->attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            return $next($request);
        }

        $this->throwFailedAuthenticationException($request);
    }

    private function throwFailedAuthenticationException($request): void
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
