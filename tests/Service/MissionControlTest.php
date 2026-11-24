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

    public function testAPlanetOutOfRangeThrowsAndKeepsTheFuel(): void
    {
        $probe = new Probe('probe', 'Probe', 5);

        try {
            new MissionControl()->launch($probe, Planet::Mars);
            self::fail('Expected an InsufficientFuelException.');
        } catch (InsufficientFuelException $e) {
            self::assertSame('Probe needs 6 fuel but only has 5.', $e->getMessage());
            self::assertSame(5, $probe->fuel);
        }
    }
}
