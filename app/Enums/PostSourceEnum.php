<?php

namespace App\Enums;

enum PostSourceEnum: string
{
    case social = 'social';
    case media = 'media';

    public static function options(): array
    {
        return [
            self::social->value => 'Social media link',
            self::media->value => 'Media on this website',
        ];
    }
}
