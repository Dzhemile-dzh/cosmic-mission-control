<?php

declare(strict_types=1);

namespace Cosmic\Model;

/**
 * A mission destination. The value is what the user types on the command line.
 */
enum Planet: string
{
    case Mercury = 'mercury';
    case Venus = 'venus';
    case Mars = 'mars';
    case Jupiter = 'jupiter';
    case Saturn = 'saturn';

    /**
     * Approximate distance from Earth at closest approach.
     */
    public function distanceInMillionKm(): int
    {
        return match ($this) {
            self::Mercury => 77,
            self::Venus => 38,
            self::Mars => 55,
            self::Jupiter => 588,
            self::Saturn => 1200,
        };
    }
}
