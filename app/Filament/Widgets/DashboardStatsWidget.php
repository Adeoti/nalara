<?php

namespace App\Filament\Widgets;

use App\Models\News;
use App\Models\NewsSource;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total News', News::count())
                ->description('All the fetched news right from day 1')
                ->descriptionIcon('heroicon-s-newspaper')
                ->color('primary')
            ,
            Stat::make('News Sources', NewsSource::count())
                ->description('All the available news sources')
                ->descriptionIcon('heroicon-s-newspaper')
                ->color('success')
            ,
            Stat::make('Users', User::count())
                ->description('All the registered users')
                ->descriptionIcon('heroicon-s-users')
                ->color('success')
            ,



        ];
    }
}
