<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\RecentNewsWidget;
use Filament\Pages\Page;

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard';

    protected static string $view = 'filament.pages.custom-dashboard';


    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStatsWidget::class,
            RecentNewsWidget::class,
        ];
    }
}
