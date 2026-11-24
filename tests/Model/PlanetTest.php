<?php

declare(strict_types=1);

namespace Cosmic\Tests\Model;

use Cosmic\Model\MissionStatus;
use Cosmic\Model\Planet;
use PHPUnit\Framework\TestCase;

final class PlanetTest extends TestCase
{
    public function testAPlanetIsFoundByWhatTheUserTypes(): void
    {
        self::assertSame(Planet::Mars, Planet::from('mars'));
        self::assertNull(Planet::tryFrom('pluto'));
    }

    public function testEveryPlanetHasAPositiveDistance(): void
    {
        foreach (Planet::cases() as $planet) {
            self::assertGreaterThan(0, $planet->distanceInMillionKm(), $planet->name);
        }
    }

    public function testEveryMissionStatusHasALabel(): void
    {
        foreach (MissionStatus::cases() as $status) {
            self::assertNotSame('', $status->label(), $status->name);
        }
    }
}
