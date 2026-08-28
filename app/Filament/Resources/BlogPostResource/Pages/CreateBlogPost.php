<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Enums\EntityTypeEnum;
use App\Filament\Resources\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = EntityTypeEnum::post->value;

        return $data;
    }
}
