<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TrafficReport;
use Illuminate\Support\Facades\DB;

class TrafficStatsOverview extends ChartWidget
{
    protected static ?string $heading = 'Laporan Kerusakan APILL Per Hari';
    

    protected function getData(): array
    {
        $data = TrafficReport::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('status', 'rusak')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Kerusakan APILL',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => '#f87171',
                    'borderColor' => '#dc2626',
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa 'line', 'bar', 'pie', dll
    }
}
