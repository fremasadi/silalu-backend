<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TrafficReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TrafficStatsOverview extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Kerusakan APILL per Jenis (7 Hari Terakhir)';

// Bikin lebar penuh
public function getColumnSpan(): int|string|array
{
    return 'full'; // atau 2, 3, 4 tergantung layout grid kamu
}
    protected function getData(): array
    {
        $sevenDaysAgo = Carbon::now()->subDays(7)->startOfDay();

        $data = TrafficReport::select('traffic.jenis_apill', DB::raw('COUNT(*) as total'))
            ->join('traffic', 'traffic_reports.traffic_id', '=', 'traffic.id')
            ->where('traffic_reports.created_at', '>=', $sevenDaysAgo)
            ->groupBy('traffic.jenis_apill')
            ->orderBy('traffic.jenis_apill')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kerusakan',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => ['#fbbf24', '#60a5fa', '#34d399'], // warna bisa disesuaikan
                ],
            ],
            'labels' => $data->pluck('jenis_apill'),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // atau 'pie', 'doughnut' untuk visual perbandingan
    }
}
