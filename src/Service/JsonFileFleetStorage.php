<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Exception\InvalidFleetDataException;
use Cosmic\Model\SpacecraftData;
use JsonException;
use Override;

final readonly class JsonFileFleetStorage implements FleetStorageInterface
{
    public function __construct(private string $filename)
    {
    }

    #[Override]
    public function fetchAll(): array
    {
        if (!is_readable($this->filename)) {
            throw InvalidFleetDataException::unreadableFile($this->filename);
        }

        try {
            $rows = json_decode(file_get_contents($this->filename), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw InvalidFleetDataException::malformedFile($this->filename, $e);
        }

        if (!is_array($rows) || !array_all($rows, static fn (mixed $row): bool => is_array($row))) {
            throw InvalidFleetDataException::malformedFile($this->filename);
        }

        return array_map(SpacecraftData::fromArray(...), array_values($rows));
    }

    #[Override]
    public function fetchOneBySlug(string $slug): ?SpacecraftData
    {
        return array_find(
            $this->fetchAll(),
            static fn (SpacecraftData $data): bool => $data->slug === $slug
        );
    }
}
