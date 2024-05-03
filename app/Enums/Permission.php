<?php

namespace App\Enums;

enum Permission: string
{
    public static function values(): array
    {
        return \collect(self::cases())->map(fn ($case): string => $case->value)->toArray();
    }
}
