<?php

declare(strict_types=1);

namespace Cosmic\Tests\Service;

use Cosmic\Model\MissionStatus;
use Cosmic\Model\Planet;
use Cosmic\Model\Probe;
use Cosmic\Service\MissionControl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MissionControlTest extends TestCase
{
    /**
     * A probe travels 10 million km per fuel unit.
     *
     * @return iterable<string, array{int, Planet, MissionStatus, int}>
     */
    public static function missions(): iterable
    {
        yield 'plenty of fuel' => [60, Planet::Mars, MissionStatus::Success, 54];
        yield 'range exactly equals the distance' => [120, Planet::Saturn, MissionStatus::Success, 0];
        yield 'just short of the distance' => [5, Planet::Mars, MissionStatus::Aborted, 5];
        yield 'empty tank' => [0, Planet::Venus, MissionStatus::Aborted, 0];
    }

    #[DataProvider('missions')]
    public function testLaunch(int $fuel, Planet $destination, MissionStatus $expectedStatus, int $expectedFuelLeft): void
    {
        $probe = new Probe('probe', 'Probe', $fuel);

        $report = new MissionControl()->launch($probe, $destination);

        self::assertSame($expectedStatus, $report->status);
        self::assertSame($expectedFuelLeft, $probe->fuel);
        self::assertSame($fuel - $expectedFuelLeft, $report->fuelBurned);
    }

    public function testASuccessfulReportDescribesTheMission(): void
    {
        $probe = new Probe('probe', 'Probe', 60);

        $report = new MissionControl()->launch($probe, Planet::Mars);

        self::assertTrue($report->isSuccess());
        self::assertSame($probe, $report->spacecraft);
        self::assertSame(Planet::Mars, $report->destination);
        self::assertNull($report->reason);
    }

    public function testAnAbortedReportExplainsWhy(): void
    {
        $report = new MissionControl()->launch(new Probe('probe', 'Probe', 5), Planet::Mars);

        self::assertFalse($report->isSuccess());
        self::assertSame('Mars is 55 million km away, but Probe can only travel 50.', $report->reason);
    }

    public function testFuelRunsDownOverRepeatedMissions(): void
    {
        $probe = new Probe('probe', 'Probe', 10);
        $missionControl = new MissionControl();

        // Venus costs a probe 4 fuel: two trips fit in a tank of 10, a third does not
        self::assertTrue($missionControl->launch($probe, Planet::Venus)->isSuccess());
        self::assertTrue($missionControl->launch($probe, Planet::Venus)->isSuccess());
        self::assertFalse($missionControl->launch($probe, Planet::Venus)->isSuccess());
        self::assertSame(2, $probe->fuel);
    }
}
