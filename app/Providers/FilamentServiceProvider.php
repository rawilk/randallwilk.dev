<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Auth\Login\Mfa\EnsureUserIsActive;
use App\Enums\UserSetting;
use App\Livewire\Profile\PreferredMfaMethod;
use App\Models\User;
use App\Support\Filament\FilamentDefaults;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Rawilk\ProfileFilament\Actions\Auth\PrepareUserSession;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;
use Rawilk\ProfileFilament\Enums\RenderHook;
use Rawilk\ProfileFilament\Filament\Pages\MfaChallenge;
use Rawilk\ProfileFilament\Filament\Pages\SudoChallenge;
use Rawilk\ProfileFilament\ProfileFilament;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        FilamentDefaults::set();

        $this->setProfileFilamentPluginDefaults();

        $this->registerRenderHooks();
    }

    protected function registerRenderHooks(): void
    {
        FilamentView::registerRenderHook(
            name: PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
            hook: fn () => view('layouts.auth.partials.login-form-before'),
        );

        FilamentView::registerRenderHook(
            name: PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            hook: fn () => view('layouts.auth.partials.login-form-after'),
        );

        FilamentView::registerRenderHook(
            name: RenderHook::MfaSettingsBefore->value,
            hook: fn () => Blade::render('@livewire("' . PreferredMfaMethod::class . '")'),
        );
    }

    protected function setProfileFilamentPluginDefaults(): void
    {
        MfaChallenge::setLayout('layouts.auth.base');
        SudoChallenge::setLayout('layouts.auth.base');

        ProfileFilament::getPreferredMfaMethodUsing(function (User $user, array $availableMethods): string {
            $preferredMethod = $user->settings()->get(UserSetting::PreferredMfaMethod);
            $keys = array_column($availableMethods, 'value');

            if (in_array($preferredMethod, $keys, true)) {
                return $preferredMethod;
            }

            // If the preferred method is not found, return the first method found.
            if (in_array(MfaChallengeMode::App->value, $keys, true)) {
                return MfaChallengeMode::App->value;
            }

            if (in_array(MfaChallengeMode::Webauthn->value, $keys, true)) {
                return MfaChallengeMode::Webauthn->value;
            }

            return MfaChallengeMode::RecoveryCode->value;
        });

        ProfileFilament::mfaAuthenticationPipelineUsing(
            fn (): array => [
                EnsureUserIsActive::class,
                PrepareUserSession::class,
            ],
        );
    }
}
