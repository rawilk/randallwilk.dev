<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login;

use App\Dto\Auth\LoginEventBag;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use SensitiveParameter;

class AttemptToAuthenticate
{
    use Concerns\ChecksUserAccess;

    public function __construct(protected readonly StatefulGuard $guard)
    {
    }

    public function __invoke(LoginEventBag $request, Closure $next)
    {
        if ($this->guard->attemptWhen(
            credentials: $this->getCredentialsFromFormData($request->data),
            callbacks: function (User $user) use ($request) {
                // Some pipes already check this, so no need to check it again.
                if (! $request->shouldCheckForUserSystemAccess()) {
                    return true;
                }

                return $this->userCanAccessSystem($user, $request->data);
            },
            remember: $this->shouldRemember($request->data),
        )) {
            return $next($request);
        }

        $this->throwFailedAuthenticationException();
    }

    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        return [
            'email' => data_get($data, 'email'),
            'password' => data_get($data, 'password'),
        ];
    }
}
