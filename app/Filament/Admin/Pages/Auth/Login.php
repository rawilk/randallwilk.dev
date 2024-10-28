<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use App\Actions\Auth\Login\AttemptToAuthenticate;
use App\Actions\Auth\Login\Mfa\EnsureUserIsActive;
use App\Actions\Auth\Login\PrepareAuthenticatedSession;
use App\Actions\Auth\Login\RedirectIfUserHasMfa;
use App\Dto\Auth\LoginEventBag;
use App\Filament\Actions\Auth\GitHubLoginAction;
use App\Filament\Concerns\Auth\HasPasswordField;
use App\Filament\Concerns\Auth\IsAuthPage;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Pipeline;
use Rawilk\ProfileFilament\Actions\Auth\PrepareUserSession;
use Rawilk\ProfileFilament\Filament\Actions\PasskeyLoginAction;

class Login extends BaseLogin
{
    use HasPasswordField;
    use IsAuthPage;

    protected static string $layout = 'layouts.auth.base';

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        return Pipeline::send(
            new LoginEventBag($this->form->getState())
        )->through([
            RedirectIfUserHasMfa::class,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ])->then(fn () => app(LoginResponse::class));
    }

    public function getTitle(): string|Htmlable
    {
        return __('pages/auth/login.title');
    }

    public function getHeading(): string|Htmlable
    {
        return __('pages/auth/login.heading');
    }

    public function passkeyLoginAction(): Action
    {
        return PasskeyLoginAction::make()
            ->icon('pf-passkey')
            ->size(ActionSize::Large)
            ->pipeThrough([
                EnsureUserIsActive::class,
                PrepareUserSession::class,
            ])
            ->extraAttributes([
                'class' => 'w-full',
            ]);
    }

    public function githubLoginAction(): Action
    {
        return GitHubLoginAction::make();
    }
}
