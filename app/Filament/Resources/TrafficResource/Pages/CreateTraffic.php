<?php

namespace App\Filament\Resources\TrafficResource\Pages;

use App\Filament\Resources\TrafficResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTraffic extends CreateRecord
{
    protected static string $resource = TrafficResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Simpan'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
