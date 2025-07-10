<?php

namespace App\Filament\Resources\ApillResource\Pages;

use App\Filament\Resources\ApillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApills extends ListRecords
{
    protected static string $resource = ApillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
