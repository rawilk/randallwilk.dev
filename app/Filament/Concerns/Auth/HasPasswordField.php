<?php

declare(strict_types=1);

namespace App\Filament\Concerns\Auth;

use Filament\Forms\Components\Component;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Rawilk\FilamentPasswordInput\Password;

trait HasPasswordField
{
    protected function getPasswordFormComponent(): Component
    {
        return Password::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()" tabindex="3">{{ __(\'filament-panels::pages/auth/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }
}
