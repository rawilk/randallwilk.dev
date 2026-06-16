<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth\PasswordReset;

use App\Filament\Concerns\Auth\IsAuthPage;
use App\Notifications\Auth\ResetPassword;
use App\Notifications\Auth\ResetPasswordInvalidUser;
use App\Support\AppConfig;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BasePage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Notification as LaravelNotification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Timebox;
use Livewire\Attributes\Locked;

use function Illuminate\Support\defer;

class RequestPasswordReset extends BasePage
{
    use IsAuthPage;

    #[Locked]
    public ?string $email = null;

    protected static string $layout = 'layouts.auth.base';

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

    public function getFormContentComponent(): Component
    {
        return parent::getFormContentComponent()
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->key('form-actions'),

                $this->loginAction(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->components([
                $this->getEmailSentComponent(),

                $this->getEmailFormComponent()
                    ->when(
                        app()->isProduction(),
                        fn (TextInput $component) => $component->rule('email:rfc,dns'),
                    )
                    ->hidden(fn (): bool => filled($this->email)),
            ]);
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
                        name: filament()->getCurrentOrDefaultPanel()->generateRouteName('auth.password-reset.reset'),
                        expiration: now()->addMinutes(AppConfig::passwordResetDecayMinutes()),
                        parameters: [
                            'token' => $token,
                            'email' => $user->getEmailForPasswordReset(),
                        ],
                    );

                    defer(function () use ($user, $token, $url) {
                        $user->notifyNow(
                            (new ResetPassword($token))
                                ->setUrl($url)
                                ->setRequestUrl(filament()->getRequestPasswordResetUrl()),
                        );
                    });
                },
            );

            if ($status === Password::RESET_LINK_SENT) {
                $timebox->returnEarly();
            }

            return $status;
        }, microseconds: AppConfig::authTimeboxDuration());

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

    public function loginAction(): Action
    {
        return parent::loginAction()
            ->label(__('pages/auth/request-password-reset.actions.login.label'))
            ->visible(fn () => auth()->guest());
    }

    protected function getFormActions(): array
    {
        return [
            $this->getRequestFormAction(),
            $this->resendAction(),
        ];
    }

    protected function getRequestFormAction(): Action
    {
        return parent::getRequestFormAction()
            ->label(__('pages/auth/request-password-reset.actions.submit.label'))
            ->hidden(fn (): bool => filled($this->email));
    }

    protected function resendAction(): Action
    {
        return Action::make('resend')
            ->label(__('pages/auth/request-password-reset.actions.resend.label'))
            ->visible(fn (): bool => filled($this->email))
            ->action(function () {
                $this->form->fill([
                    'email' => $this->email,
                ]);

                $this->email = null;
            });
    }

    protected function getEmailSentComponent(): Component
    {
        return Text::make(
            fn () => new HtmlString(Blade::render(<<<'HTML'
            <div class="space-y-3">
                {{
                    str(__('pages/auth/request-password-reset.alerts.sent.description', ['email' => e($email)]))->markdown()->toHtmlString()
                }}
            </div>
            HTML, [
                'email' => e($this->email),
            ])),
        )
            ->visible(fn () => filled($this->email));
    }
}
