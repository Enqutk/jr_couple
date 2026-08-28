<?php

namespace App\Media;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DynamicPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $modelName = strtolower(class_basename($media->model_type));
        return $modelName . 's/' . $media->collection_name . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}
