<?php

namespace Saivra\MeUi\Support;

use InvalidArgumentException;

final class ThemeOptions
{
    public const DEFAULTS = [
        'density' => 'comfortable',
        'palette' => 'slate',
        'accent' => 'panel',
        'radius' => 'rounded',
        'surface' => 'elevated',
        'sidebar' => 'soft',
        'workspace' => 'inset',
        'contrast' => 'standard',
    ];

    public const CHOICES = [
        'density' => ['comfortable', 'compact'],
        'palette' => ['slate', 'zinc', 'stone'],
        'accent' => ['panel', 'blue', 'indigo', 'violet', 'teal', 'rose'],
        'radius' => ['compact', 'rounded', 'soft'],
        'surface' => ['elevated', 'outlined'],
        'sidebar' => ['soft', 'solid'],
        'workspace' => ['inset', 'flush'],
        'contrast' => ['standard', 'strong'],
    ];

    public static function assertValid(string $name, mixed $value): string
    {
        if (! is_string($value) || ! in_array($value, self::CHOICES[$name] ?? [], true)) {
            throw new InvalidArgumentException('Invalid MeUI '.$name.' option.');
        }

        return $value;
    }

    /** @param array<string, mixed> $values @return array<string, string> */
    public static function sanitize(array $values): array
    {
        return array_filter($values, fn (mixed $value, string $name): bool => is_string($value)
            && in_array($value, self::CHOICES[$name] ?? [], true), ARRAY_FILTER_USE_BOTH);
    }
}
