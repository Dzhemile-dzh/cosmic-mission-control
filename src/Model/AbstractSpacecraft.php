<?php

declare(strict_types=1);

namespace Cosmic\Model;

use Cosmic\Exception\InsufficientFuelException;
use InvalidArgumentException;

abstract class AbstractSpacecraft
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

    public private(set) int $fuel {
        set {
            if ($value < 0) {
                throw new InvalidArgumentException('Fuel cannot be negative.');
            }

            $this->fuel = $value;
        }
    }

    public function __construct(
        public readonly string $slug,
        string $name,
        int $fuel,
    ) {
        $this->name = $name;
        $this->fuel = $fuel;
    }

    abstract public function getType(): string;

    abstract public function millionKmPerFuelUnit(): int;

    public function rangeInMillionKm(): int
    {
        return $this->fuel * $this->millionKmPerFuelUnit();
    }

    public function fuelNeededFor(Planet $planet): int
    {
        return (int) ceil($planet->distanceInMillionKm() / $this->millionKmPerFuelUnit());
    }

    /**
     * @throws InsufficientFuelException
     */
    public function burnFuel(int $amount): void
    {
        if ($amount > $this->fuel) {
            throw new InsufficientFuelException($this->name, $amount, $this->fuel);
        }

        $this->fuel -= $amount;
    }
}
