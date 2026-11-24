<?php

declare(strict_types=1);

namespace Cosmic\Model;

use Cosmic\Exception\InsufficientFuelException;
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

    public private(set) int $fuel {
        set {
            if ($value < 0) {
                throw new InvalidArgumentException('Fuel cannot be negative.');
            }

            $this->fuel = $value;
        }
    }

    /**
     * @param string $slug
     * @param string $name
     * @param int $fuel
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

    abstract public function millionKmPerFuelUnit(): int;

    public function rangeInMillionKm(): int
    {
        return $this->fuel * $this->millionKmPerFuelUnit();
    }

    public function canReach(Planet $planet): bool
    {
        return $this->rangeInMillionKm() >= $planet->distanceInMillionKm();
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
        if ($amount < 0) {
            throw new InvalidArgumentException('Cannot burn a negative amount of fuel.');
        }

        if ($amount > $this->fuel) {
            throw InsufficientFuelException::forBurn($this->name, $amount, $this->fuel);
        }

        $this->fuel -= $amount;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->name;
    }
}
