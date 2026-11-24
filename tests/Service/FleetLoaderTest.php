<?php

declare(strict_types=1);

namespace Cosmic\Tests\Service;

use Cosmic\Exception\UnknownSpacecraftException;
use Cosmic\Model\CargoHauler;
use Cosmic\Model\CrewedShip;
use Cosmic\Model\Probe;
use Cosmic\Model\SpacecraftData;
use Cosmic\Model\SpacecraftType;
use Cosmic\Service\FleetLoader;
use Cosmic\Tests\Support\InMemoryFleetStorage;
use PHPUnit\Framework\TestCase;

final class FleetLoaderTest extends TestCase
{
    private FleetLoader $fleetLoader;

    protected function setUp(): void
    {
        $this->fleetLoader = new FleetLoader(new InMemoryFleetStorage(
            new SpacecraftData('voyager', 'Voyager', SpacecraftType::Probe, 60),
            new SpacecraftData('odyssey', 'Odyssey', SpacecraftType::Crewed, 60, 20),
            new SpacecraftData('atlas', 'Atlas', SpacecraftType::Cargo, 60, 500),
        ));
    }

    public function testEachTypeBecomesTheMatchingClass(): void
    {
        $fleet = iterator_to_array($this->fleetLoader->getFleet());

        self::assertCount(3, $fleet);
        self::assertInstanceOf(Probe::class, $fleet[0]);
        self::assertInstanceOf(CrewedShip::class, $fleet[1]);
        self::assertInstanceOf(CargoHauler::class, $fleet[2]);
    }

    public function testCargoCapacityIsLoaded(): void
    {
        $atlas = $this->fleetLoader->getBySlug('atlas');

        self::assertInstanceOf(CargoHauler::class, $atlas);
        self::assertSame(500, $atlas->cargoCapacityInTons);
    }

    public function testTheSlugIsMatchedRegardlessOfCaseAndSpaces(): void
    {
        self::assertSame('Atlas', $this->fleetLoader->getBySlug('  ATLAS ')->name);
    }

    public function testAnUnknownSlugThrows(): void
    {
        $this->expectException(UnknownSpacecraftException::class);
        $this->expectExceptionMessage('"voyagr"');

        $this->fleetLoader->getBySlug('voyagr');
    }
}
