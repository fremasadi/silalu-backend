<?php

// app/Filament/Pages/Dashboard.php
namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Pelaporan Traffic';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?int $navigationSort = 1;
    
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\TrafficStatsOverview::class,
            \App\Filament\Widgets\DailyReportsOverview::class,
            \App\Filament\Widgets\TrafficReportChart::class,
            // \App\Filament\Widgets\TrafficReportStatusChart::class,
            \App\Filament\Widgets\RecentTrafficReports::class,
            // \App\Filament\Widgets\TopTrafficLocations::class,
        ];
    }
    
    public function getColumns(): int | string | array
    {
        return 2;
    }
}