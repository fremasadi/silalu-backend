<?php

namespace App\Filament\Widgets;

use App\Models\Traffic;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopTrafficLocations extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Lokasi dengan Laporan Terbanyak';
    
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $topLocations = Traffic::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->limit(10)
            ->get();

        $labels = $topLocations->pluck('name')->toArray();
        $data = $topLocations->pluck('reports_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Laporan',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(20, 184, 166, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}