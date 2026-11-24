<?php

declare(strict_types=1);

namespace Cosmic\Model;

/**
 * One stored spacecraft, exactly as a storage hands it over.
 */
final readonly class SpacecraftData
{
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
     */
    public static function fromArray(array $row): self
    {
        return new self(
            slug: (string) $row['slug'],
            name: (string) $row['name'],
            type: SpacecraftType::from((string) $row['type']),
            fuel: (int) $row['fuel'],
            // optional: a probe has no cargo hold
            cargoCapacityInTons: (int) ($row['cargo_capacity'] ?? 0),
        );
    }
}
