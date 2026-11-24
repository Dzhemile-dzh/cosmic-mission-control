<?php

declare(strict_types=1);

namespace Cosmic\Exception;

use RuntimeException;
use Throwable;

/**
 * The stored fleet data is broken: a problem with the data file, not with
 * what the user typed.
 */
final class InvalidFleetDataException extends RuntimeException implements CosmicExceptionInterface
{
    public static function unreadableFile(string $filename): self
    {
        return new self(sprintf('Cannot read the fleet file "%s".', $filename));
    }

    /**
     * @param Throwable|null $previous the original JSON error, kept for debugging
     */
    public static function malformedFile(string $filename, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('The fleet file "%s" is not a valid JSON list of spacecraft.', $filename),
            previous: $previous
        );
    }

    public static function missingField(string $field): self
    {
        return new self(sprintf('A spacecraft in the fleet data has no "%s".', $field));
    }

    public static function unknownType(string $type): self
    {
        return new self(sprintf('The fleet data contains an unknown spacecraft type "%s".', $type));
    }
}
