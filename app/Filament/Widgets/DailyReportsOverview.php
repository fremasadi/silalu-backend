<?php

// app/Filament/Widgets/DailyReportsOverview.php (Widget tambahan)
namespace App\Filament\Widgets;

use App\Models\TrafficReport;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class DailyReportsOverview extends ChartWidget
{
    protected static ?string $heading = 'Laporan 7 Hari Terakhir';
    
    protected function getData(): array
    {
        $days = [];
        $reportCounts = [];
        
        // Ambil data 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('d M');
            
            $count = TrafficReport::whereDate('created_at', $date->toDateString())
                ->count();
            $reportCounts[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Laporan Harian',
                    'data' => $reportCounts,
                    'backgroundColor' => 'rgba(168, 85, 247, 0.8)',
                    'borderColor' => 'rgb(168, 85, 247)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}