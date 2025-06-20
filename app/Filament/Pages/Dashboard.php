<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\User;
use App\Models\Traffic;
use App\Models\TrafficReport;
use Illuminate\Support\Facades\DB;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Dashboard Admin';
    protected static ?string $navigationLabel = 'Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            // Tidak menggunakan widget terpisah, semua akan di-handle di view
        ];
    }

    protected function getStats(): array
    {
        $totalReports = TrafficReport::count();
        $pendingReports = TrafficReport::where('status', 'pending')->count();
        $confirmedReports = TrafficReport::where('status', 'confirmed')->count();
        $rejectedReports = TrafficReport::where('status', 'rejected')->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalOfficers = User::where('role', 'petugas')->count();
        $totalTrafficPoints = Traffic::count();

        return [
            [
                'title' => 'Total Laporan',
                'value' => $totalReports,
                'description' => 'Semua laporan lalu lintas',
                'icon' => 'heroicon-m-document-text',
                'color' => 'primary',
            ],
            [
                'title' => 'Laporan Pending',
                'value' => $pendingReports,
                'description' => 'Menunggu konfirmasi',
                'icon' => 'heroicon-m-clock',
                'color' => 'warning',
            ],
            [
                'title' => 'Laporan Dikonfirmasi',
                'value' => $confirmedReports,
                'description' => 'Sudah dikonfirmasi petugas',
                'icon' => 'heroicon-m-check-circle',
                'color' => 'success',
            ],
            [
                'title' => 'Laporan Ditolak',
                'value' => $rejectedReports,
                'description' => 'Ditolak oleh petugas',
                'icon' => 'heroicon-m-x-circle',
                'color' => 'danger',
            ],
            [
                'title' => 'Total User',
                'value' => $totalUsers,
                'description' => 'Pengguna aplikasi',
                'icon' => 'heroicon-m-users',
                'color' => 'info',
            ],
            [
                'title' => 'Total Petugas',
                'value' => $totalOfficers,
                'description' => 'Petugas aktif',
                'icon' => 'heroicon-m-shield-check',
                'color' => 'success',
            ],
            [
                'title' => 'Titik Lalu Lintas',
                'value' => $totalTrafficPoints,
                'description' => 'Total lokasi pemantauan',
                'icon' => 'heroicon-m-map-pin',
                'color' => 'primary',
            ],
        ];
    }

    protected function getChartData(): array
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

    protected function getRecentReports()
    {
        return TrafficReport::with(['traffic', 'confirmedBy'])
            ->latest()
            ->limit(10)
            ->get();
    }

    // Override method untuk pass data ke view
    protected function getViewData(): array
    {
        return [
            'stats' => $this->getStats(),
            'chartData' => $this->getChartData(),
            'recentReports' => $this->getRecentReports(),
        ];
    }
}