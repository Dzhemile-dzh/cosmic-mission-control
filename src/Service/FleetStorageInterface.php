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
     *
     * @throws InvalidFleetDataException
     */
    public function fetchOneBySlug(string $slug): ?SpacecraftData;
}
