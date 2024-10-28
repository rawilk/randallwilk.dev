<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth\PasswordReset;

use App\Actions\Auth\LogoutAction;
use App\Enums\SessionAlert;
use App\Filament\Concerns\Auth\IsAuthPage;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\PasswordResetResponse;
use Filament\Pages\Auth\PasswordReset\ResetPassword as BaseResetPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Rawilk\FilamentPasswordInput\Password as PasswordInput;

class ResetPassword extends BaseResetPassword
{
    use IsAuthPage;

    protected static string $layout = 'layouts.auth.base';

    protected static string $view = 'filament.admin.pages.auth.password-reset.reset-password';

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
