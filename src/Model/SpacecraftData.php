<?php

declare(strict_types=1);

namespace Cosmic\Model;

use Cosmic\Exception\InvalidFleetDataException;

/**
 * One stored spacecraft, exactly as a storage hands it over.
 */
final readonly class SpacecraftData
{
    private const array REQUIRED_FIELDS = ['slug', 'name', 'type', 'fuel'];

    public function __construct(
        public string $slug,
        public string $name,
        public SpacecraftType $type,
        public int $fuel,
        public int $cargoCapacityInTons = 0,
    ) {
    }

    /**
     * Builds the object from a raw row, casting every value in one place.
     *
     * @param array<string, mixed> $row
     *
     * @throws InvalidFleetDataException
     */
    public static function fromArray(array $row): self
    {
        $missingField = array_find(
            self::REQUIRED_FIELDS,
            static fn (string $field): bool => !isset($row[$field])
        );

        if ($missingField !== null) {
            throw InvalidFleetDataException::missingField($missingField);
        }

        $type = (string) $row['type'];

        return new self(
            slug: (string) $row['slug'],
            name: (string) $row['name'],
            type: SpacecraftType::tryFrom($type) ?? throw InvalidFleetDataException::unknownType($type),
            fuel: (int) $row['fuel'],
            // optional: a probe has no cargo hold
            cargoCapacityInTons: (int) ($row['cargo_capacity'] ?? 0),
        );
    }
}
