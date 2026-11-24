<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Exception\InvalidFleetDataException;
use Cosmic\Model\SpacecraftData;

interface FleetStorageInterface
{
    /**
     * @return list<SpacecraftData>
     *
     * @throws InvalidFleetDataException
     */
    public function fetchAll(): array;

    /**
     * Returns the spacecraft with this slug, or null if there is none.
     *
     * @throws InvalidFleetDataException
     */
    public function fetchOneBySlug(string $slug): ?SpacecraftData;
}
