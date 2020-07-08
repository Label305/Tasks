<?php

namespace Label305\Tasks\Enums;


class Result
{

    public const CREATED = 'created';
    public const STARTED = 'started';
    public const FINISHED = 'finished';
    public const WARNING = 'warning';
    public const ERROR = 'error';

    private const PRIORITY = [
        self::CREATED => 1,
        self::STARTED => 2,
        self::FINISHED => 3,
        self::WARNING => 4,
        self::ERROR => 5,
    ];

    public static function hasHigherPriority(string $a, string $b): bool
    {
        return self::toPriority($b) > self::toPriority($a);
    }

    private static function toPriority(string $state): int
    {
        return self::PRIORITY[$state];
    }
}
