{{-- resources/views/filament/pages/dashboard.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
            @foreach ($stats as $stat)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full 
                            @if($stat['color'] === 'primary') bg-blue-100 dark:bg-blue-900
                            @elseif($stat['color'] === 'warning') bg-yellow-100 dark:bg-yellow-900
                            @elseif($stat['color'] === 'success') bg-green-100 dark:bg-green-900
                            @elseif($stat['color'] === 'danger') bg-red-100 dark:bg-red-900
                            @elseif($stat['color'] === 'info') bg-cyan-100 dark:bg-cyan-900
                            @endif">
                            <x-heroicon-m-document-text class="w-6 h-6 
                                @if($stat['color'] === 'primary') text-blue-600 dark:text-blue-300
                                @elseif($stat['color'] === 'warning') text-yellow-600 dark:text-yellow-300
                                @elseif($stat['color'] === 'success') text-green-600 dark:text-green-300
                                @elseif($stat['color'] === 'danger') text-red-600 dark:text-red-300
                                @elseif($stat['color'] === 'info') text-cyan-600 dark:text-cyan-300
                                @endif" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stat['value']) }}</h3>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $stat['title'] }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $stat['description'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Chart Widget --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Laporan Bulanan</h3>
            <div class="relative">
                <canvas id="reportsChart" width="400" height="200"></canvas>
            </div>
        </div>

        {{-- Recent Reports Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Masalah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dikonfirmasi Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($recentReports as $report)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($report->foto)
                                        <img src="{{ Storage::url($report->foto) }}" alt="Foto Laporan" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                            <x-heroicon-m-photo class="w-6 h-6 text-gray-500" />
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->traffic->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ Str::limit($report->masalah, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($report->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($report->status === 'rejected') bg-red-100 text-red-800
                                        @endif">
                                        @if($report->status === 'pending') Pending
                                        @elseif($report->status === 'confirmed') Dikonfirmasi
                                        @elseif($report->status === 'rejected') Ditolak
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $report->confirmedBy->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $report->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <x-heroicon-m-plus class="w-6 h-6 text-blue-600 dark:text-blue-300" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Lokasi</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tambah titik lalu lintas baru</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="/admin/traffic/create" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Tambah Lokasi
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <x-heroicon-m-check-circle class="w-6 h-6 text-green-600 dark:text-green-300" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kelola Laporan</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Lihat semua laporan lalu lintas</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="/admin/traffic-reports" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Kelola Laporan
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <x-heroicon-m-users class="w-6 h-6 text-purple-600 dark:text-purple-300" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kelola User</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Kelola pengguna dan petugas</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="/admin/users" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Kelola User
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900">
                        <x-heroicon-m-chart-bar class="w-6 h-6 text-orange-600 dark:text-orange-300" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Detail</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Lihat laporan detail dan statistik</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#" 
                       class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 active:bg-orange-900 focus:outline-none focus:border-orange-900 focus:ring ring-orange-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('reportsChart').getContext('2d');
            const chartData = @json($chartData);
            
            new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Laporan Lalu Lintas per Bulan'
                        },
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-filament-panels::page>