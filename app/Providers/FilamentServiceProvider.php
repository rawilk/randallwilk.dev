<?php

declare(strict_types=1);

namespace App\Providers;

use App\Rules\UniqueEmail;
use App\Support\Filament\FilamentDefaults;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Rawilk\ProfileFilament\Filament\Schemas\Forms\Inputs\NewEmailInput;

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
    }

    protected function setProfileFilamentPluginDefaults(): void
    {
        NewEmailInput::configureUsing(
            fn (NewEmailInput $component) => $component
                ->when(
                    app()->isProduction(),
                    fn (NewEmailInput $component) => $component->rule('email:rfc,dns'),
                )
                ->dehydrateStateUsing(fn (string $state) => Str::lower($state))
                ->rule(fn () => UniqueEmail::make()->withUser(Filament::auth()->user()))
        );
    }
}
