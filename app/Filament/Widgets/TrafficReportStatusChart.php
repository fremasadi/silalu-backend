<?php

// app/Filament/Widgets/TrafficReportStatusChart.php
namespace App\Filament\Widgets;

use App\Models\TrafficReport;
use Filament\Widgets\ChartWidget;

class TrafficReportStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Laporan Traffic';
    
    protected function getData(): array
    {
        $pending = TrafficReport::where('status', 'pending')->count();
        $confirmed = TrafficReport::where('status', 'confirmed')->count();
        $rejected = TrafficReport::where('status', 'rejected')->count();

        return [
            'datasets' => [
                [
                    'data' => [$pending, $confirmed, $rejected],
                    'backgroundColor' => [
                        'rgb(245, 158, 11)',  // Yellow for pending
                        'rgb(34, 197, 94)',   // Green for confirmed
                        'rgb(239, 68, 68)',   // Red for rejected
                    ],
                ],
            ],
            'labels' => ['Pending', 'Confirmed', 'Rejected'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}