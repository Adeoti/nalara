<?php

namespace App\Filament\Resources\CentralAppManagerResource\Pages;

use App\Filament\Resources\CentralAppManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentralAppManagers extends ListRecords
{
    protected static string $resource = CentralAppManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
