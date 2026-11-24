<?php

declare(strict_types=1);

namespace Cosmic\Model;

use InvalidArgumentException;

trait CarriesCargoTrait
{
    public int $cargoCapacityInTons = 0 {
        set {
            if ($value < 0) {
                throw new InvalidArgumentException('Cargo capacity cannot be negative.');
            }

            $this->cargoCapacityInTons = $value;
        }
    }
}
