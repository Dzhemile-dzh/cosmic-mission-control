<?php

declare(strict_types=1);

namespace Cosmic\Console;

use Cosmic\Model\Planet;
use Override;

final readonly class PlanetsCommand implements CommandInterface
{
    private const string ROW_FORMAT = '%-10s %-10s %9s';

    #[Override]
    public function name(): string
    {
        return 'planets';
    }

    #[Override]
    public function usage(): string
    {
        return 'planets';
    }

    #[Override]
    public function description(): string
    {
        return 'List the destinations';
    }

    #[Override]
    public function run(array $arguments, Output $output): int
    {
        $output->line(sprintf(self::ROW_FORMAT, 'SLUG', 'NAME', 'DISTANCE'));

        foreach (Planet::cases() as $planet) {
            $output->line(sprintf(
                self::ROW_FORMAT,
                $planet->value,
                $planet->name,
                $planet->distanceInMillionKm()
            ));
        }

        $output->line();
        $output->line('Distance is in million km from Earth.');

        return Application::EXIT_OK;
    }
}
