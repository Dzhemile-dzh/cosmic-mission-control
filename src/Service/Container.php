<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Console\Application;
use Cosmic\Console\FleetCommand;
use Cosmic\Console\LaunchCommand;
use Cosmic\Console\Output;
use Cosmic\Console\PlanetsCommand;

/**
 * Builds every object once, on first use, and wires them together.
 */
final class Container
{
    private ?Output $output = null;

    private ?FleetStorageInterface $fleetStorage = null;

    private ?FleetLoader $fleetLoader = null;

    private ?MissionControl $missionControl = null;

    private ?Application $application = null;

    public function __construct(private readonly string $fleetFile)
    {
    }

    public function getOutput(): Output
    {
        return $this->output ??= new Output();
    }

    public function getFleetStorage(): FleetStorageInterface
    {
        return $this->fleetStorage ??= new JsonFileFleetStorage($this->fleetFile);
    }

    public function getFleetLoader(): FleetLoader
    {
        return $this->fleetLoader ??= new FleetLoader($this->getFleetStorage());
    }

    public function getMissionControl(): MissionControl
    {
        return $this->missionControl ??= new MissionControl();
    }

    public function getApplication(): Application
    {
        return $this->application ??= new Application(
            $this->getOutput(),
            new FleetCommand($this->getFleetLoader()),
            new PlanetsCommand(),
            new LaunchCommand($this->getFleetLoader(), $this->getMissionControl()),
        );
    }
}
