<?php

declare(strict_types=1);

namespace Cosmic\Exception;

use InvalidArgumentException;

final class UnknownSpacecraftException extends InvalidArgumentException implements CosmicExceptionInterface
{
    public static function forSlug(string $slug): self
    {
        return new self(sprintf('There is no spacecraft called "%s" in the fleet.', $slug));
    }
}
