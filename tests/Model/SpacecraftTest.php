<?php

declare(strict_types=1);

namespace Cosmic\Tests\Model;

use Cosmic\Exception\InsufficientFuelException;
use Cosmic\Model\CargoHauler;
use Cosmic\Model\Planet;
use Cosmic\Model\Probe;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SpacecraftTest extends TestCase
{
    public function testABlankNameIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Probe('voyager', '   ', 60);
    }

    public function testNegativeFuelIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Probe('voyager', 'Voyager', -1);
    }

    public function testRangeDependsOnTheKindOfSpacecraft(): void
    {
        self::assertSame(600, new Probe('p', 'P', 60)->rangeInMillionKm());
        self::assertSame(120, new CargoHauler('c', 'C', 60)->rangeInMillionKm());
    }

    public function testFuelNeededIsRoundedUp(): void
    {
        // Mars is 55 away and a probe does 10 per fuel unit: 5.5 becomes 6
        self::assertSame(6, new Probe('p', 'P', 60)->fuelNeededFor(Planet::Mars));
    }

    public function testBurningTooMuchFuelThrows(): void
    {
        $probe = new Probe('p', 'P', 10);

        $this->expectException(InsufficientFuelException::class);

        $probe->burnFuel(11);
    }

    public function testCargoCapacityCannotBeNegative(): void
    {
        $hauler = new CargoHauler('c', 'C', 60);

        $this->expectException(InvalidArgumentException::class);

        $hauler->cargoCapacityInTons = -1;
    }
}
