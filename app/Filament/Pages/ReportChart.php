<?php

namespace App\Filament\Pages;

class ReportChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Laporan Bulanan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = TrafficReport::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count'),
            'status'
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month', 'status')
        ->orderBy('month')
        ->get();

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $pending = array_fill(1, 12, 0);
        $confirmed = array_fill(1, 12, 0);
        $rejected = array_fill(1, 12, 0);

        foreach ($data as $item) {
            if ($item->status === 'pending') {
                $pending[$item->month] = $item->count;
            } elseif ($item->status === 'confirmed') {
                $confirmed[$item->month] = $item->count;
            } elseif ($item->status === 'rejected') {
                $rejected[$item->month] = $item->count;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pending',
                    'data' => array_values($pending),
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#f59e0b',
                ],
                [
                    'label' => 'Dikonfirmasi',
                    'data' => array_values($confirmed),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                ],
                [
                    'label' => 'Ditolak',
                    'data' => array_values($rejected),
                    'backgroundColor' => '#ef4444',
                    'borderColor' => '#ef4444',
                ],
            ],
            'labels' => array_values($months),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
