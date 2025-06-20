<?php

namespace App\Filament\Widgets;

use App\Models\TrafficReport;
use Filament\Widgets\ChartWidget;

class TrafficReportStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Laporan Traffic';
    
    protected static ?int $sort = 3;

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
                        'rgb(239, 68, 68)', // red for pending
                        'rgb(34, 197, 94)', // green for confirmed
                        'rgb(107, 114, 128)', // gray for rejected
                    ],
                ],
            ],
            'labels' => ['Pending', 'Dikonfirmasi', 'Ditolak'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}