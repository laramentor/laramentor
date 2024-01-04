<?php

namespace App\Enums;

enum SocialProvider: string
{
    case GITHUB = 'github';
    case GOOGLE = 'google';
    case BITBUCKET = 'bitbucket';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
