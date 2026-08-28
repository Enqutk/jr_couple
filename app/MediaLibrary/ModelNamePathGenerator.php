<?php
namespace App\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class ModelNamePathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $modelName = strtolower(class_basename($media->model_type));
        
        // Special handling for Hero model
        if ($modelName === 'hero') {
            return "heros/";
        }
        
        // Special handling for Service model
        if ($modelName === 'service') {
            return "services/";
        }
        
        return "images/{$modelName}/";
    }

    public function getPathForConversions(Media $media): string
    {
        $modelName = strtolower(class_basename($media->model_type));
        
        // Special handling for Hero model
        if ($modelName === 'hero') {
            return "heros/conversions/";
        }
        
        // Special handling for Service model
        if ($modelName === 'service') {
            return "services/conversions/";
        }
        
        return "images/{$modelName}/conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        $modelName = strtolower(class_basename($media->model_type));
        
        // Special handling for Hero model
        if ($modelName === 'hero') {
            return "heros/responsive-images/";
        }
        
        // Special handling for Service model
        if ($modelName === 'service') {
            return "services/responsive-images/";
        }
        
        return "images/{$modelName}/responsive-images/";
    }
}
