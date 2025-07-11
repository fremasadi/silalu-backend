<?php

namespace App\Filament\Resources\TrafficReportResource\Pages;

use App\Filament\Resources\TrafficReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrafficReport extends CreateRecord
{
    protected static string $resource = TrafficReportResource::class;

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
