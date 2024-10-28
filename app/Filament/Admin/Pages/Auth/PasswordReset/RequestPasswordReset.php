<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth\PasswordReset;

use App\Filament\Concerns\Auth\IsAuthPage;
use App\Notifications\Auth\ResetPassword;
use App\Notifications\Auth\ResetPasswordInvalidUser;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseComponent;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification as LaravelNotification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Timebox;
use Livewire\Attributes\Locked;

class RequestPasswordReset extends BaseComponent
{
    use IsAuthPage;

    #[Locked]
    public ?string $email = null;

    protected static string $layout = 'layouts.auth.base';

    protected static string $view = 'filament.admin.pages.auth.password-reset.request-password-reset';

    public function mount(): void
    {
        $this->form->fill([
            'email' => auth()->user()?->email,
        ]);
    }

    public function getTitle(): string|Htmlable
    {
        return __('pages/auth/request-password-reset.heading');
    }

    public function getHeading(): string|Htmlable
    {
        return filled($this->email)
            ? ''
            : __('pages/auth/request-password-reset.heading');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return filled($this->email)
            ? null
            : __('pages/auth/request-password-reset.subheading');
    }

    public function request(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return;
        }

        $data = $this->form->getState();

        $status = App::make(Timebox::class)->call(callback: function (Timebox $timebox) use ($data) {
            $status = Password::broker(filament()->getAuthPasswordBroker())->sendResetLink(
                $data,
                function (CanResetPassword $user, string $token): void {
                    // We're going to generate the reset link ourselves, so we can make it temporary.
                    $url = URL::temporarySignedRoute(
                        name: filament()->getCurrentPanel()->generateRouteName('auth.password-reset.reset'),
                        expiration: now()->addMinutes(config('auth.passwords.users.expire')),
                        parameters: [
                            'token' => $token,
                            'email' => $user->getEmailForPasswordReset(),
                        ],
                    );

                    defer(function () use ($user, $token, $url) {
                        $user->notifyNow(
                            (new ResetPassword($token))
                                ->setUrl($url)
                                ->setRequestUrl(filament()->getRequestPasswordResetUrl())
                        );
                    });
                },
            );

            if ($status === Password::RESET_LINK_SENT) {
                $timebox->returnEarly();
            }

            return $status;
        }, microseconds: config('randallwilk.timebox_duration'));

        if ($status === Password::RESET_THROTTLED) {
            Notification::make()
                ->title(__($status))
                ->danger()
                ->send();

            return;
        }

        // If the user doesn't exist in the system, we'll send an email notifying them of the attempt.
        if ($status !== Password::RESET_LINK_SENT) {
            defer(function () use ($data) {
                LaravelNotification::route('mail', $data['email'])
                    ->notifyNow(new ResetPasswordInvalidUser(filament()->getLoginUrl()));
            });
        }

        $this->email = $data['email'];

        $this->form->fill();
    }

    public function resend(): void
    {
        $this->email = null;
    }

    public function loginAction(): Action
    {
        return parent::loginAction()
            ->label(__('pages/auth/request-password-reset.actions.login.label'));
    }

    protected function getRequestFormAction(): Action
    {
        return parent::getRequestFormAction()
            ->label(__('pages/auth/request-password-reset.actions.submit.label'));
    }
}
