<?php

// app/Filament/Widgets/TrafficReportChart.php
namespace App\Filament\Widgets;

use App\Models\TrafficReport;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class TrafficReportChart extends ChartWidget
{
    protected static ?string $heading = 'Laporan Traffic per Bulan';
    
    protected function getData(): array
    {
        $months = [];
        $reportCounts = [];
        
        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $count = TrafficReport::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $reportCounts[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Laporan',
                    'data' => $reportCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}