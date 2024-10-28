<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login;

use App\Dto\Auth\LoginEventBag;
use App\Models\User;
use App\Responses\Auth\TwoFactorRedirectResponse;
use Closure;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Arr;
use Rawilk\ProfileFilament\Facades\Mfa;
use SensitiveParameter;

class RedirectIfUserHasMfa
{
    use Concerns\ChecksUserAccess;

    public function __construct(protected readonly StatefulGuard $guard)
    {
    }

    public function __invoke(LoginEventBag $request, Closure $next)
    {
        $user = $this->getUser($request);

        $request->setUser($user);

        if (! $this->userCanAccessSystem($user, $request->data)) {
            $this->throwFailedAuthenticationException();
        }

        // Lets our future pipes know that this check doesn't need to be run again.
        $request->userSystemAccessCheckWasPerformed();

        if ($this->userHasMfaEnabled($user)) {
            return $this->mfaChallengeResponse($user, $request->data);
        }

        return $next($request);
    }

    protected function getUser(LoginEventBag $request): ?User
    {
        return tap($this->resolveUser($request), function (?User $user) use ($request) {
            if (
                (! $user) ||
                (! $this->guard->getProvider()->validateCredentials($user, ['password' => data_get($request->data, 'password')]))
            ) {
                $this->fireFailedEvent($request->data, $user);

                $this->throwFailedAuthenticationException();
            }
        });
    }

    protected function resolveUser(LoginEventBag $request): ?User
    {
        if ($user = $request->user()) {
            return $user;
        }

        return User::whereEmail(data_get($request->data, 'email'))->first();
    }

    protected function fireFailedEvent(#[SensitiveParameter] $data, ?User $user): void
    {
        event(new Failed('web', $user, Arr::only($data, ['email', 'password'])));
    }

    protected function mfaChallengeResponse(User $user, #[SensitiveParameter] array $data)
    {
        Mfa::pushChallengedUser(
            user: $user,
            remember: $this->shouldRemember($data),
        );

        return app(TwoFactorRedirectResponse::class);
    }

    protected function userHasMfaEnabled(User $user): bool
    {
        return Mfa::userHasMfaEnabled($user);
    }
}
