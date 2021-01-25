<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Services\Auth\LoginRateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Validation\ValidationException;

final class EnsureLoginIsNotThrottled
{
    private LoginRateLimiter $limiter;

    public function __construct(LoginRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Throttle the login requests if the user has attempted to login
     * too many times and failed.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle($request, $next)
    {
        if (! $this->limiter->tooManyAttempts($request)) {
            return $next($request);
        }

        event(new Lockout($request));

        with($this->limiter->availableIn($request), static function ($seconds) {
            throw ValidationException::withMessages([
                'email' => [
                    __('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ]),
                ],
            ]);
        });
    }
}
