<?php

declare(strict_types=1);

namespace Cosmic\Model;

use InvalidArgumentException;
use Override;
use Stringable;

abstract class AbstractSpacecraft implements Stringable
{
    public string $name {
        set {
            $value = trim($value);

            if ($value === '') {
                throw new InvalidArgumentException('A spacecraft needs a name.');
            }

            $this->name = $value;
        }
    }

    /**
     * Readable by anyone, but only the spacecraft itself changes its fuel.
     */
    public private(set) int $fuel {
        set {
            if ($value < 0) {
                throw new InvalidArgumentException('Fuel cannot be negative.');
            }

            $this->fuel = $value;
        }
    }

    /**
     * @param string $slug what the user types on the command line, e.g. "voyager"
     */
    public function __construct(
        public readonly string $slug,
        string $name,
        int $fuel,
    ) {
        $this->name = $name;
        $this->fuel = $fuel;
    }

    abstract public function getType(): string;

    /**
     * How far one unit of fuel carries this kind of spacecraft.
     */
    abstract public function millionKmPerFuelUnit(): int;

    public function rangeInMillionKm(): int
    {
        return $this->fuel * $this->millionKmPerFuelUnit();
    }

    public function canReach(Planet $planet): bool
    {
        return $this->rangeInMillionKm() >= $planet->distanceInMillionKm();
    }

    #[Override]
    public function __toString(): string
    {
        return $this->name;
    }
}
