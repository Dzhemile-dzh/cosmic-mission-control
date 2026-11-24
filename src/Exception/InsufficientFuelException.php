<?php

declare(strict_types=1);

namespace Cosmic\Exception;

final class InsufficientFuelException extends CosmicException
{
    public function __construct(string $spacecraftName, int $needed, int $available)
    {
        parent::__construct(sprintf('%s needs %d fuel but only has %d.', $spacecraftName, $needed, $available));
    }
}
