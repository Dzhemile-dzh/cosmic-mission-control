<?php

declare(strict_types=1);

namespace Cosmic\Exception;

use RuntimeException;

final class InsufficientFuelException extends RuntimeException implements CosmicExceptionInterface
{
    public static function forBurn(string $spacecraftName, int $needed, int $available): self
    {
        return new self(sprintf(
            '%s cannot burn %d fuel: only %d left in the tank.',
            $spacecraftName,
            $needed,
            $available
        ));
    }
}
