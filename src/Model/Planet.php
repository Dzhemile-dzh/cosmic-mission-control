<?php

declare(strict_types=1);

namespace Cosmic\Model;

enum Planet: string
{
    case Mercury = 'mercury';
    case Venus = 'venus';
    case Mars = 'mars';
    case Jupiter = 'jupiter';
    case Saturn = 'saturn';

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
