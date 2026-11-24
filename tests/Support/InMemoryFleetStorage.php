<?php

declare(strict_types=1);

namespace Cosmic\Tests\Support;

use Cosmic\Model\SpacecraftData;
use Cosmic\Service\FleetStorageInterface;
use Override;

/**
 * A storage that needs no file, so tests control exactly which spacecraft exist.
 */
final readonly class InMemoryFleetStorage implements FleetStorageInterface
{
    /**
     * @var list<SpacecraftData>
     */
    private array $spacecraft;

    public function __construct(SpacecraftData ...$spacecraft)
    {
        $this->spacecraft = array_values($spacecraft);
    }

    #[Override]
    public function fetchAll(): array
    {
        return $this->spacecraft;
    }

    #[Override]
    public function fetchOneBySlug(string $slug): ?SpacecraftData
    {
        return array_find(
            $this->spacecraft,
            static fn (SpacecraftData $data): bool => $data->slug === $slug
        );
    }
}
