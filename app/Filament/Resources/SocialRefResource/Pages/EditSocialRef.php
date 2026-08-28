<?php

namespace App\Filament\Resources\SocialRefResource\Pages;

use App\Filament\Resources\SocialRefResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;


class EditSocialRef extends EditRecord
{

    protected static string $resource = SocialRefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
