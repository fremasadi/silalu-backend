<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrafficReportResource\Pages;
use App\Filament\Resources\TrafficReportResource\RelationManagers;
use App\Models\TrafficReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class TrafficReportResource extends Resource
{
    protected static ?string $model = TrafficReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('traffic_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('masalah')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('foto')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('traffic.name')
                    ->label('Lokasi')
                    ->sortable(),

                // Tables\Columns\TextColumn::make('foto')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),   // 👈 Tambah ini

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function view(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('traffic.name')
                ->label('Lokasi')
                ->disabled(),

            Forms\Components\Textarea::make('masalah')
                ->disabled(),

            Forms\Components\TextInput::make('status')
                ->disabled(),

                Forms\Components\Placeholder::make('Foto')
                ->content(function ($record) {
                    if ($record->foto) {
                        return new HtmlString('<img src="' . asset('storage/' . $record->foto) . '" width="200" />');
                    }
                    return 'Tidak ada foto';
                })
                ->columnSpanFull()
                ->hiddenLabel(),
        ])
        ->columns(1);
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
            'index' => Pages\ListTrafficReports::route('/'),
            'create' => Pages\CreateTrafficReport::route('/create'),
            'edit' => Pages\EditTrafficReport::route('/{record}/edit'),
        ];
    }
}
