<?php

declare(strict_types=1);

namespace Cosmic\Console;

use Cosmic\Exception\UnknownPlanetException;
use Cosmic\Exception\UsageException;
use Cosmic\Model\Planet;
use Cosmic\Service\FleetLoader;
use Cosmic\Service\MissionControl;
use Override;

final readonly class LaunchCommand implements CommandInterface
{
    public function __construct(
        private FleetLoader $fleetLoader,
        private MissionControl $missionControl,
    ) {
    }

    #[Override]
    public function name(): string
    {
        return 'launch';
    }

    #[Override]
    public function usage(): string
    {
        return 'launch <spacecraft> <planet>';
    }

    #[Override]
    public function description(): string
    {
        return 'Send a spacecraft to a planet';
    }

    #[Override]
    public function run(array $arguments, Output $output): int
    {
        $slug = $arguments[0] ?? throw UsageException::missingArgument('spacecraft', $this->usage());
        $planetName = $arguments[1] ?? throw UsageException::missingArgument('planet', $this->usage());

        $spacecraft = $this->fleetLoader->getBySlug($slug);
        $planet = Planet::tryFrom(strtolower(trim($planetName)))
            ?? throw UnknownPlanetException::forName($planetName);

        $report = $this->missionControl->launch($spacecraft, $planet);

        if (!$report->isSuccess()) {
            $output->line(sprintf('%s: %s', $report->status->label(), $report->reason));

            return Application::EXIT_FAILURE;
        }

        $output->line(sprintf('%s: %s reached %s.', $report->status->label(), $spacecraft, $planet->name));
        $output->line(sprintf('Fuel burned: %d (%d left).', $report->fuelBurned, $spacecraft->fuel));

        return Application::EXIT_OK;
    }
}
