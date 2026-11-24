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
    public function testTheNameIsTrimmed(): void
    {
        self::assertSame('Voyager', new Probe('voyager', '  Voyager ', 60)->name);
    }

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

    public function testFuelNeededIsRoundedUpToAWholeUnit(): void
    {
        // Mars is 55 away: 5.5 units for a probe, 27.5 for a hauler
        self::assertSame(6, new Probe('p', 'P', 60)->fuelNeededFor(Planet::Mars));
        self::assertSame(28, new CargoHauler('c', 'C', 60)->fuelNeededFor(Planet::Mars));
    }

    public function testBurningFuelLowersTheTank(): void
    {
        $probe = new Probe('p', 'P', 10);

        $probe->burnFuel(4);

        self::assertSame(6, $probe->fuel);
    }

    public function testBurningMoreFuelThanThereIsThrowsAndKeepsTheFuel(): void
    {
        $probe = new Probe('p', 'P', 10);

        try {
            $probe->burnFuel(11);
            self::fail('Expected an InsufficientFuelException.');
        } catch (InsufficientFuelException) {
            self::assertSame(10, $probe->fuel);
        }
    }

    public function testCargoCapacityCannotBeNegative(): void
    {
        $hauler = new CargoHauler('c', 'C', 60);

        $this->expectException(InvalidArgumentException::class);

        $hauler->cargoCapacityInTons = -1;
    }

    public function testCanCarryUpToItsCapacity(): void
    {
        $hauler = new CargoHauler('c', 'C', 60);
        $hauler->cargoCapacityInTons = 500;

        self::assertTrue($hauler->canCarry(500));
        self::assertFalse($hauler->canCarry(501));
    }
}
