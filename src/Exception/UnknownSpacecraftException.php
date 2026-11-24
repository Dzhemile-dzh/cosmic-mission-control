<?php

declare(strict_types=1);

namespace Cosmic\Exception;

final class UnknownSpacecraftException extends CosmicException
{
    public function __construct(string $slug)
    {
        parent::__construct(sprintf('There is no spacecraft called "%s" in the fleet.', $slug));
    }
}
