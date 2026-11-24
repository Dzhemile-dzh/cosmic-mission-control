<?php

declare(strict_types=1);

namespace Cosmic\Exception;

use InvalidArgumentException;

/**
 * The command line itself was wrong (e.g. a missing argument).
 */
final class UsageException extends InvalidArgumentException implements CosmicExceptionInterface
{
    public static function missingArgument(string $argument, string $usage): self
    {
        return new self(sprintf('Missing argument "%s". Usage: %s', $argument, $usage));
    }
}
