<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Pelaporan Traffic';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\TrafficStatsOverview::class,
            \App\Filament\Widgets\TrafficReportChart::class,
            \App\Filament\Widgets\TrafficReportStatusChart::class,
            \App\Filament\Widgets\RecentTrafficReports::class,
            \App\Filament\Widgets\TopTrafficLocations::class,
        ];
    }
}