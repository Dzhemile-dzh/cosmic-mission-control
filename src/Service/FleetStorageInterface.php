<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Exception\UnknownSpacecraftException;
use Cosmic\Model\AbstractSpacecraft;

interface FleetStorageInterface
{
    /**
     * @return list<AbstractSpacecraft>
     */
    public function all(): array;

    /**
     * @throws UnknownSpacecraftException
     */
    public function find(string $slug): AbstractSpacecraft;
}
