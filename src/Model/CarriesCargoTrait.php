<?php

declare(strict_types=1);

namespace Cosmic\Model;

use InvalidArgumentException;

/**
 * For spacecraft with a cargo hold. Not every spacecraft has one (a probe
 * does not), so this cannot live in AbstractSpacecraft.
 */
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

    public function canCarry(int $tons): bool
    {
        return $tons >= 0 && $tons <= $this->cargoCapacityInTons;
    }
}
