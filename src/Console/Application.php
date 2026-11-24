<?php

declare(strict_types=1);

namespace Cosmic\Console;

use Cosmic\Exception\CosmicException;
use Cosmic\Exception\UnknownPlanetException;
use Cosmic\Model\Planet;
use Cosmic\Service\FleetStorageInterface;
use Cosmic\Service\MissionControl;

final readonly class Application
{
    public function __construct(
        private FleetStorageInterface $fleetStorage,
        private MissionControl $missionControl,
    ) {
    }

    /**
     * @param list<string> $argv
     *
     * @return int the exit code: 0 for success, 1 for an error
     */
    public function run(array $argv): int
    {
        try {
            match ($argv[1] ?? 'help') {
                'help' => $this->help(),
                'fleet' => $this->fleet(),
                'planets' => $this->planets(),
                'launch' => $this->launch($argv[2] ?? null, $argv[3] ?? null),
                default => throw new CosmicException(sprintf('Unknown command "%s". Run "php bin/cosmic help".', $argv[1])),
            };

            return 0;
        } catch (CosmicException $e) {
            fwrite(STDERR, $e->getMessage().PHP_EOL);

            return 1;
        }
    }

    private function help(): void
    {
        echo 'Cosmic Mission Control'.PHP_EOL;
        echo PHP_EOL;
        echo '  php bin/cosmic fleet                          List the spacecraft'.PHP_EOL;
        echo '  php bin/cosmic planets                        List the destinations'.PHP_EOL;
        echo '  php bin/cosmic launch <spacecraft> <planet>   Send a spacecraft to a planet'.PHP_EOL;
    }

    private function fleet(): void
    {
        foreach ($this->fleetStorage->all() as $spacecraft) {
            printf(
                '%-10s %-14s fuel %-4d range %d million km'.PHP_EOL,
                $spacecraft->slug,
                $spacecraft->getType(),
                $spacecraft->fuel,
                $spacecraft->rangeInMillionKm()
            );
        }
    }

    private function planets(): void
    {
        foreach (Planet::cases() as $planet) {
            printf('%-10s %d million km'.PHP_EOL, $planet->value, $planet->distanceInMillionKm());
        }
    }

    private function launch(?string $slug, ?string $planetName): void
    {
        if ($slug === null || $planetName === null) {
            throw new CosmicException('Usage: php bin/cosmic launch <spacecraft> <planet>');
        }

        $spacecraft = $this->fleetStorage->find(strtolower($slug));
        $planet = Planet::tryFrom(strtolower($planetName)) ?? throw new UnknownPlanetException($planetName);

        $report = $this->missionControl->launch($spacecraft, $planet);

        printf(
            '%s reached %s. Fuel burned: %d (%d left).'.PHP_EOL,
            $spacecraft->name,
            $planet->name,
            $report->fuelBurned,
            $spacecraft->fuel
        );
    }
}
