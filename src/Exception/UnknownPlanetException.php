<?php

declare(strict_types=1);

namespace Cosmic\Exception;

use Cosmic\Model\Planet;
use InvalidArgumentException;

final class UnknownPlanetException extends InvalidArgumentException implements CosmicExceptionInterface
{
    public static function forName(string $name): self
    {
        return new self(sprintf(
            'Unknown destination "%s". Choose one of: %s.',
            $name,
            implode(', ', array_column(Planet::cases(), 'value'))
        ));
    }
}
