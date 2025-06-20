<?php

namespace App\Filament\Pages;

class RecentReports extends BaseTableWidget
{
    protected static ?string $heading = 'Laporan Terbaru';
    protected static ?int $sort = 3;

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
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->size(50)
                    ->circular(),
                
                TextColumn::make('traffic.name')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('masalah')
                    ->label('Masalah')
                    ->limit(50)
                    ->searchable(),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => fn ($state): bool => $state === 'pending',
                        'success' => fn ($state): bool => $state === 'confirmed',
                        'danger' => fn ($state): bool => $state === 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'confirmed' => 'Dikonfirmasi',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                
                TextColumn::make('confirmedBy.name')
                    ->label('Dikonfirmasi Oleh')
                    ->default('-'),
                
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}