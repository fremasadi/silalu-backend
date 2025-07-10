<?php

namespace App\Filament\Resources\ApillResource\Pages;

use App\Filament\Resources\ApillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApill extends EditRecord
{
    protected static string $resource = ApillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
