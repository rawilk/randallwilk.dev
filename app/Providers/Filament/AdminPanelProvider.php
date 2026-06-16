<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Admin as AdminFilament;
use App\Filament\Plugins\EnvironmentIndicatorPlugin;
use App\Filament\Plugins\HorizonPlugin;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Rawilk\ProfileFilament\Auth\Multifactor\App\AppAuthenticationProvider;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\WebauthnProvider;
use Rawilk\ProfileFilament\Auth\Sudo\App\SudoAppAuthenticationProvider;
use Rawilk\ProfileFilament\Auth\Sudo\Password\SudoPasswordProvider;
use Rawilk\ProfileFilament\Auth\Sudo\Webauthn\SudoWebauthnProvider;
use Rawilk\ProfileFilament\ProfileFilamentPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(AdminFilament\Pages\Auth\Login::class)
            ->passwordReset(
                requestAction: AdminFilament\Pages\Auth\PasswordReset\RequestPasswordReset::class,
                resetAction: AdminFilament\Pages\Auth\PasswordReset\ResetPassword::class,
            )
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandLogo(fn () => new HtmlString(Blade::render(<<<'HTML'
            <x-logo type="dual" class="logo h-full w-auto text-gray-800 dark:text-white" />
            HTML,
            )))
            ->spa(false)
            ->databaseNotifications()
            ->databaseNotificationsPolling(null)
            ->databaseTransactions()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->plugin(HorizonPlugin::make())
            ->plugin(EnvironmentIndicatorPlugin::make())
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems([
                Action::make('backToSite')
                    ->label(__('Back to site'))
                    ->url(fn () => route('home'))
                    ->openUrlInNewTab()
                    ->icon(Heroicon::ArrowLeft),
            ])
            ->renderHook(
                name: PanelsRenderHook::HEAD_END,
                hook: fn () => view('layouts.admin.partials.head-end'),
            )
            ->renderHook(
                name: PanelsRenderHook::SCRIPTS_AFTER,
                hook: fn () => view('layouts.partials.admin-scripts'),
            )
            ->renderHook(
                name: PanelsRenderHook::PAGE_START,
                hook: fn () => view('layouts.admin.partials.session-alert'),
            );

        $panel
            ->emailVerification()
            ->emailChangeVerification()
            ->plugin($this->getProfilePlugin());

        return $panel;
    }

    protected function getProfilePlugin(): ProfileFilamentPlugin
    {
        return ProfileFilamentPlugin::make()
            ->requirePasswordConfirmation(false)
            ->profileInfoPage(
                AdminFilament\Clusters\Profile\ProfileInfo::make()->slug('user'),
            )
            ->useProfileMenuLabel(__('Your settings'))
            ->useProfileMenuIcon(Heroicon::OutlinedCog6Tooth)
            ->multiFactorAuthentication(providers: [
                AppAuthenticationProvider::make(),
                WebauthnProvider::make(),
            ], challengeAction: AdminFilament\Pages\Auth\MultiFactorChallenge::class)
            ->sudoMode(providers: [
                SudoAppAuthenticationProvider::make(),
                SudoWebauthnProvider::make(),
                SudoPasswordProvider::make(),
            ]);
    }
}
