<?php

namespace App\Enums;

enum ContentType: string
{
    case PAGE = 'page';

    case COLLECTION = 'collection';

    case COMPONENT = 'component';

    public static function values(): array
    {
        return \collect(self::cases())->map(fn ($case): string => $case->value)->toArray();
    }

    public static function labels(): array
    {
        return \collect(self::cases())->map(fn ($case): string => ucfirst($case->value))->toArray();
    }

    public static function options(): array
    {
        return \collect(self::cases())->map(fn ($case): array => [
            'value' => $case->value,
            'label' => ucfirst($case->value),
        ])->toArray();
    }
}
