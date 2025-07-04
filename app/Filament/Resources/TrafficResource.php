<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrafficResource\Pages;
use App\Filament\Resources\TrafficResource\RelationManagers;
use App\Models\Traffic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Kecamatan;

class TrafficResource extends Resource
{
    protected static ?string $model = Traffic::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Data Lalu Lintas';

    protected static ?string $modelLabel = 'Lalu Lintas';

    protected static ?string $pluralModelLabel = 'Data Lalu Lintas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Infomasi Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lokasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukan Nama Lokasi'),
                        Select::make('kecamatan_id')
                            ->label('Kecamatan')
                            ->relationship('kecamatan', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Pilih Kecamatan'),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step('any')
                                    ->required()
                                    ->placeholder('-7.8169')
                                    ->readOnly()
                                    ->helperText('Click on map to auto-fill coordinates')
                                    ->id('latitude-input'),

                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step('any')
                                    ->required()
                                    ->placeholder('112.0176')
                                    ->readOnly()
                                    ->helperText('Klik pada peta untuk mengisi koordinat secara otomatis')
                                    ->id('longitude-input'),
                            ]),

                        Forms\Components\ViewField::make('map')
                            ->label('Location Map')
                            ->view('filament.forms.components.leaflet-map')
                            ->viewData(fn ($record) => [
                                'latitude' => $record?->latitude ?? -7.8169,
                                'longitude' => $record?->longitude ?? 112.0176,
                            ])
                            ->dehydrated(false),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lokasi')
                    ->searchable()
                    ->sortable(),

                // Tables\Columns\TextColumn::make('latitude')
                //     ->label('Latitude')
                //     ->sortable()
                //     ->formatStateUsing(fn ($state) => number_format($state, 6)),

                // Tables\Columns\TextColumn::make('longitude')
                //     ->label('Longitude')
                //     ->sortable()
                //     ->formatStateUsing(fn ($state) => number_format($state, 6)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    // ->toggleable(isToggledHiddenByDefault: true),

                // Tables\Columns\TextColumn::make('updated_at')
                //     ->label('Updated')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTraffic::route('/'),
            'create' => Pages\CreateTraffic::route('/create'),
            'view' => Pages\ViewTraffic::route('/{record}'),
            'edit' => Pages\EditTraffic::route('/{record}/edit'),
        ];
    }
}