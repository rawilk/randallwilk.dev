<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth\PasswordReset;

use App\Actions\Auth\LogoutAction;
use App\Enums\SessionAlert;
use App\Filament\Concerns\Auth\IsAuthPage;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\PasswordResetResponse;
use Filament\Auth\Pages\PasswordReset\ResetPassword as BasePage;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Rawilk\FilamentPasswordInput\Password as PasswordInput;

class ResetPassword extends BasePage
{
    use IsAuthPage;

    protected static string $layout = 'layouts.auth.base';

    public function mount(?string $email = null, ?string $token = null): void
    {
        $this->token = $token ?? request()->query('token');

        $this->form->fill([
            'email' => $email ?? request()->query('email'),
        ]);
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('pages/auth/reset-password.subheading', ['email' => e($this->email)]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getSessionAlertComponent(),
                $this->getPasswordFormComponent(),
            ]);
    }

    public function resetPassword(): ?PasswordResetResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        if ($this->isResetPasswordRateLimited($this->email)) {
            return null;
        }

        $data = $this->form->getState();

        $data['email'] = $this->email;
        $data['token'] = $this->token;

        $status = Password::broker(filament()->getAuthPasswordBroker())->reset(
            $data,
            function (CanResetPassword|User $user) use ($data) {
                $user->update([
                    'password' => $data['password'],
                    'remember_token' => Str::random(60),
                ]);

                event(new PasswordReset($user));
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            // If we're signed in currently, sign the user out for security purposes.
            if (auth()->check()) {
                app(LogoutAction::class)();
            }

            SessionAlert::Success->flash(__($status));

            return app(PasswordResetResponse::class);
        }

        SessionAlert::Error->flash(__($status));

        return null;
    }

    protected function getSessionAlertComponent(): Component
    {
        $enum = SessionAlert::Error;

        return Text::make(
            fn () => new HtmlString(Blade::render(<<<'HTML'
            <x-feedback.alert
                :color="$enum->color()"
            >
                {{ $enum->message() }}

                <x-slot:actions>
                    <x-feedback.alert-action :href="$url">
                        {{ __('pages/auth/reset-password.actions.request_password_reset.label') }}
                    </x-feedback.alert-action>
                </x-slot:actions>
            </x-feedback.alert>
            HTML, [
                'enum' => $enum,
                'url' => Filament::getRequestPasswordResetUrl(),
            ])),
        )
            ->visible(fn (): bool => $enum->exists())
            ->extraAttributes([
                'class' => 'w-full',
            ]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return PasswordInput::make('password')
            ->label(__('pages/auth/reset-password.form.password.label'))
            ->copyable()
            ->regeneratePassword()
            ->inlineSuffix()
            ->required()
            ->autofocus()
            ->rule(PasswordRule::default());
    }
}
