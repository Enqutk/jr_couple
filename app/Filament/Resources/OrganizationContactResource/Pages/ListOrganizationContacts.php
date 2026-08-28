<?php

namespace App\Filament\Resources\OrganizationContactResource\Pages;

use App\Filament\Resources\OrganizationContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationContacts extends ListRecords
{
    protected static string $resource = OrganizationContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
