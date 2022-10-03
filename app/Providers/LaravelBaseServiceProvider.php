<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Auth\ResetUserPasswordAction;
use App\Actions\LaravelBase\UpdatePasswordAction;
use App\Actions\LaravelBase\UpdateUserProfileInformationAction;
use App\Actions\Users\DeleteUserAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Rawilk\LaravelBase\LaravelBase;

final class LaravelBaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        LaravelBase::findAppTimezoneUsing(fn () => config('site.timezone'));

        $this->registerViews();
        $this->registerBindings();
        $this->setDefaultPasswordRules();
    }

    private function registerViews(): void
    {
        LaravelBase::loginView(function () {
            return view('livewire.auth.login')
                ->layout('layouts.auth.base', [
                    'title' => __('auth.login.title'),
                ]);
        });

        LaravelBase::requestPasswordResetLinkView(function () {
            return view('livewire.auth.passwords.email')
                ->layout('layouts.auth.base', [
                    'title' => __('auth.passwords.email.title'),
                ]);
        });

        LaravelBase::resetPasswordView(function () {
            return view('livewire.auth.passwords.reset')
                ->layout('layouts.auth.base', [
                    'title' => __('auth.passwords.reset.title'),
                ]);
        });

        LaravelBase::confirmPasswordView(function () {
            return view('livewire.auth.confirm-password')
                ->layout('layouts.auth.base', [
                    'title' => __('auth.passwords.confirm.title'),
                ]);
        });

        LaravelBase::twoFactorChallengeView(function () {
            return view('livewire.auth.two-factor-challenge')
                ->layout('layouts.auth.base', [
                    'title' => __('base::2fa.challenge.title'),
                ]);
        });
    }

    private function registerBindings(): void
    {
        LaravelBase::resetUserPasswordsUsing(ResetUserPasswordAction::class);
        LaravelBase::updateUserProfileInformationUsing(UpdateUserProfileInformationAction::class);
        LaravelBase::updateUserPasswordsUsing(UpdatePasswordAction::class);
        LaravelBase::deleteUsersUsing(DeleteUserAction::class);
    }

    private function setDefaultPasswordRules(): void
    {
        Password::defaults(function () {
            return Password::min(6)
                ->when($this->app->isProduction(), function (Password $rule) {
                    return $rule->uncompromised();
                });
        });
    }
}
