<?php

declare(strict_types=1);

namespace Cosmic\Console;

use Cosmic\Service\FleetLoader;
use Override;

final readonly class FleetCommand implements CommandInterface
{
    private const string ROW_FORMAT = '%-10s %-10s %-14s %6s %8s';

    public function __construct(private FleetLoader $fleetLoader)
    {
    }

    #[Override]
    public function name(): string
    {
        return 'fleet';
    }

    #[Override]
    public function usage(): string
    {
        return 'fleet';
    }

    #[Override]
    public function description(): string
    {
        return 'List the spacecraft in the fleet';
    }

    #[Override]
    public function run(array $arguments, Output $output): int
    {
        $fleet = $this->fleetLoader->getFleet();

        $output->line(sprintf(self::ROW_FORMAT, 'SLUG', 'NAME', 'TYPE', 'FUEL', 'RANGE'));

        foreach ($fleet as $spacecraft) {
            $output->line(sprintf(
                self::ROW_FORMAT,
                $spacecraft->slug,
                $spacecraft->name,
                $spacecraft->getType(),
                $spacecraft->fuel,
                $spacecraft->rangeInMillionKm()
            ));
        }

        $output->line();
        $output->line('Range is in million km.');

        return Application::EXIT_OK;
    }
}
