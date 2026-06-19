<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use App\Filament\Concerns\Auth\IsAuthPage;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Rawilk\FilamentPasswordInput\Password;
use Rawilk\ProfileFilament\Auth\Login\Concerns\HandlesLoginForm;

class Login extends BaseLogin
{
    use HandlesLoginForm;
    use IsAuthPage;

    protected static string $layout = 'layouts.auth.base';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE),
                $this->alternativesComponent(),
                $this->getFormContentComponent(),
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER),
            ]);
    }

    public function getTitle(): string|Htmlable
    {
        return __('pages/auth/login.title');
    }

    public function getHeading(): string|Htmlable
    {
        return __('pages/auth/login.heading');
    }

    protected function alternativesComponent(): Component
    {
        return Group::make([
            // Passkey login
            Text::make(new HtmlString(Blade::render(<<<'HTML'
            <div class="text-center mt-2">
                <x-profile-filament::passkey-login
                    panel="{{ filament()->getId() }}"
                >
                    <x-filament::button
                        x-show="! processing"
                        color="gray"
                        class="w-full"
                        icon="pf-passkey"
                        size="lg"
                    >
                        {{ __('profile-filament::auth/multi-factor/webauthn/passkeys.login.actions.authenticate.label') }}
                    </x-filament::button>
                </x-profile-filament::passkey-login>
            </div>
            HTML,
            )))
                ->extraAttributes([
                    'class' => 'w-full',
                ]),

            Text::make(new HtmlString(Blade::render(<<<'HTML'
            <div class="relative -mb-4 -mt-4 flex py-5 items-center text-sm">
                <div class="grow border-t border-gray-200 dark:border-gray-600"></div>
                <span class="shrink mx-4 text-gray-600 dark:text-gray-200">Or use email</span>
                <div class="grow border-t border-gray-200 dark:border-gray-600"></div>
            </div>
            HTML,
            )))
                ->extraAttributes([
                    'class' => 'w-full',
                ]),
        ]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return Password::make('password')
            ->label(__('filament-panels::auth/pages/login.form.password.label'))
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()" tabindex="-1"> {{ __(\'filament-panels::auth/pages/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
            ->autocomplete('current-password')
            ->required();
    }
}
