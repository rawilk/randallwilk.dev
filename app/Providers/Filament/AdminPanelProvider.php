<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Admin as AdminFilament;
use App\Filament\Plugins\EnvironmentIndicatorPlugin;
use App\Filament\Plugins\HorizonPlugin;
use App\Livewire\Profile\ConnectedAccounts;
use App\Livewire\Profile\ProfileInfo;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Rawilk\ProfileFilament\Features;
use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo as ProfileInfoPage;
use Rawilk\ProfileFilament\Livewire\Profile\ProfileInfo as PackageProfileInfo;
use Rawilk\ProfileFilament\ProfileFilamentPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
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
            HTML)))
            ->spa(false)

            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])

            ->plugin(HorizonPlugin::make())
            ->plugin(EnvironmentIndicatorPlugin::make())

            ->plugin(
                ProfileFilamentPlugin::make()
                    ->useMfaMiddleware(false)
                    ->profile(
                        components: [
                            ConnectedAccounts::class,
                        ],
                    )
                    ->swapComponent(ProfileInfoPage::class, PackageProfileInfo::class, ProfileInfo::class)
                    ->features(
                        Features::defaults()
                            ->requirePasswordConfirmationToUpdatePassword(false),
                    ),
            )

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])

            ->userMenuItems([
                MenuItem::make()
                    ->label(__('Back to site'))
                    ->url(fn () => route('home'))
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-arrow-left'),
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
    }
}
