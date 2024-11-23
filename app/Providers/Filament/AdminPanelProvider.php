<?php

namespace App\Providers\Filament;

use App\Filament\Resources\BorrowResource\Widgets\CurrentMonthBorrowsWidget;
use App\Filament\Resources\ItemResource\Widgets\AvailableItemsWidget;
use App\Filament\Widgets\CurrentActiveBorrowsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandLogo(asset('images/logo_smk.png'))
            ->favicon(asset('images/logo_smk.png'))
            ->brandLogoHeight('100px')
            ->sidebarCollapsibleOnDesktop()
            ->default()
            ->id('dashboard')
            ->path('/dashboard')
            ->login()
            ->profile()
            ->registration()
            ->passwordReset()
            ->topbar(true)
            ->emailVerification()
            ->colors([
                'primary' => Color::hex('#2c3262'),
                'secondary' => Color::hex('#f4a60c'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // CurrentMonthBorrowsWidget::class,
                CurrentActiveBorrowsWidget::class,
                AvailableItemsWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('borrow')
                    ->label('Borrows')
                    ->icon('heroicon-o-queue-list'),
                NavigationGroup::make('filament_shield')
                    ->label('Filament Shield'),
                NavigationGroup::make('items')
                    ->label('Items')
                    ->icon('heroicon-o-rectangle-stack')
                    ->collapsed(),
                NavigationGroup::make('users')
                    ->label('Users')
                    ->collapsed(),

            ])
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
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ]);
    }
}
