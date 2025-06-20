<?php

namespace App\Filament\Widgets;

use App\Models\TrafficReport;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTrafficReports extends BaseWidget
{
    protected static ?string $heading = 'Laporan Traffic Terbaru';
    
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TrafficReport::query()
                    ->with(['traffic', 'confirmedBy'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('traffic.name')
                    ->label('Lokasi Traffic')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('masalah')
                    ->label('Masalah')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'pending',
                        'success' => 'confirmed',
                        'secondary' => 'rejected',
                    ]),
                    
                Tables\Columns\TextColumn::make('confirmedBy.name')
                    ->label('Dikonfirmasi Oleh')
                    ->default('-'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Laporan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}