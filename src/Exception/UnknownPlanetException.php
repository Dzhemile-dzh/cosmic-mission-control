<?php

declare(strict_types=1);

namespace Cosmic\Exception;

final class UnknownPlanetException extends CosmicException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Unknown destination "%s". Run "php bin/cosmic planets" to see the choices.', $name));
    }
}
