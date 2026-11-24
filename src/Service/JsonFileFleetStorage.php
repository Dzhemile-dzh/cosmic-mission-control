<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Exception\CosmicException;
use Cosmic\Exception\UnknownSpacecraftException;
use Cosmic\Model\AbstractSpacecraft;
use Cosmic\Model\CargoHauler;
use Cosmic\Model\CrewedShip;
use Cosmic\Model\Probe;
use JsonException;
use Override;

final readonly class JsonFileFleetStorage implements FleetStorageInterface
{
    public function __construct(private string $filename)
    {
    }

    #[Override]
    public function all(): array
    {
        if (!is_readable($this->filename)) {
            throw new CosmicException(sprintf('Cannot read the fleet file "%s".', $this->filename));
        }

        try {
            $rows = json_decode(file_get_contents($this->filename), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new CosmicException(sprintf('The fleet file "%s" is not valid JSON.', $this->filename), previous: $e);
        }

        return array_map($this->createSpacecraft(...), $rows);
    }

    #[Override]
    public function find(string $slug): AbstractSpacecraft
    {
        return array_find(
            $this->all(),
            static fn (AbstractSpacecraft $spacecraft): bool => $spacecraft->slug === $slug
        ) ?? throw new UnknownSpacecraftException($slug);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function createSpacecraft(array $row): AbstractSpacecraft
    {
        $spacecraft = match ($row['type']) {
            'probe' => new Probe($row['slug'], $row['name'], $row['fuel']),
            'crewed' => new CrewedShip($row['slug'], $row['name'], $row['fuel']),
            'cargo' => new CargoHauler($row['slug'], $row['name'], $row['fuel']),
            default => throw new CosmicException(sprintf('Unknown spacecraft type "%s" in the fleet file.', $row['type'])),
        };

        if ($spacecraft instanceof CrewedShip || $spacecraft instanceof CargoHauler) {
            $spacecraft->cargoCapacityInTons = $row['cargo_capacity'] ?? 0;
        }

        return $spacecraft;
    }
}
