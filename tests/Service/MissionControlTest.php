<?php

declare(strict_types=1);

namespace Cosmic\Tests\Service;

use Cosmic\Exception\InsufficientFuelException;
use Cosmic\Model\Planet;
use Cosmic\Model\Probe;
use Cosmic\Service\MissionControl;
use PHPUnit\Framework\TestCase;

final class MissionControlTest extends TestCase
{
    public function testALaunchBurnsFuelAndReportsIt(): void
    {
        $probe = new Probe('probe', 'Probe', 60);

        $report = new MissionControl()->launch($probe, Planet::Mars);

        self::assertSame(6, $report->fuelBurned);
        self::assertSame(54, $probe->fuel);
        self::assertSame(Planet::Mars, $report->destination);
    }

    public function testAPlanetOutOfRangeThrows(): void
    {
        $probe = new Probe('probe', 'Probe', 5);

        $this->expectException(InsufficientFuelException::class);

        new MissionControl()->launch($probe, Planet::Mars);
    }
}
