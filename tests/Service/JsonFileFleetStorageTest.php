<?php

declare(strict_types=1);

namespace Cosmic\Tests\Service;

use Cosmic\Exception\CosmicException;
use Cosmic\Exception\UnknownSpacecraftException;
use Cosmic\Model\CargoHauler;
use Cosmic\Service\JsonFileFleetStorage;
use PHPUnit\Framework\TestCase;

final class JsonFileFleetStorageTest extends TestCase
{
    private const string FLEET_FILE = __DIR__.'/../../resources/fleet.json';

    public function testItLoadsTheWholeFleet(): void
    {
        self::assertCount(4, new JsonFileFleetStorage(self::FLEET_FILE)->all());
    }

    public function testItFindsASpacecraftBySlug(): void
    {
        $atlas = new JsonFileFleetStorage(self::FLEET_FILE)->find('atlas');

        self::assertInstanceOf(CargoHauler::class, $atlas);
        self::assertSame(500, $atlas->cargoCapacityInTons);
    }

    public function testAnUnknownSlugThrows(): void
    {
        $this->expectException(UnknownSpacecraftException::class);

        new JsonFileFleetStorage(self::FLEET_FILE)->find('voyagr');
    }

    public function testAMissingFileThrows(): void
    {
        $this->expectException(CosmicException::class);

        new JsonFileFleetStorage(__DIR__.'/no-such-file.json')->all();
    }
}
