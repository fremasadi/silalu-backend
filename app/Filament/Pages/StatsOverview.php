<?php

namespace App\Filament\Pages;


class StatsOverview extends BaseStatsWidget
{
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
            BaseStatsWidget\Stat::make('Total Laporan', $totalReports)
                ->description('Semua laporan lalu lintas')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            
            BaseStatsWidget\Stat::make('Laporan Pending', $pendingReports)
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            BaseStatsWidget\Stat::make('Laporan Dikonfirmasi', $confirmedReports)
                ->description('Sudah dikonfirmasi petugas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            BaseStatsWidget\Stat::make('Laporan Ditolak', $rejectedReports)
                ->description('Ditolak oleh petugas')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            
            BaseStatsWidget\Stat::make('Total User', $totalUsers)
                ->description('Pengguna aplikasi')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            BaseStatsWidget\Stat::make('Total Petugas', $totalOfficers)
                ->description('Petugas aktif')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),
            
            BaseStatsWidget\Stat::make('Titik Lalu Lintas', $totalTrafficPoints)
                ->description('Total lokasi pemantauan')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('primary'),
        ];
    }
}
