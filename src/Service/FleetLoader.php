<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Exception\UnknownSpacecraftException;
use Cosmic\Model\AbstractSpacecraft;
use Cosmic\Model\CargoHauler;
use Cosmic\Model\CrewedShip;
use Cosmic\Model\Fleet;
use Cosmic\Model\Probe;
use Cosmic\Model\SpacecraftData;
use Cosmic\Model\SpacecraftType;

/**
 * Turns stored spacecraft data into real spacecraft objects.
 */
final readonly class FleetLoader
{
    public function __construct(private FleetStorageInterface $fleetStorage)
    {
    }

    public function getFleet(): Fleet
    {
        return new Fleet(...array_map($this->createSpacecraft(...), $this->fleetStorage->fetchAll()));
    }

    /**
     * @throws UnknownSpacecraftException
     */
    public function getBySlug(string $slug): AbstractSpacecraft
    {
        $data = $this->fleetStorage->fetchOneBySlug(strtolower(trim($slug)))
            ?? throw UnknownSpacecraftException::forSlug($slug);

        return $this->createSpacecraft($data);
    }

    private function createSpacecraft(SpacecraftData $data): AbstractSpacecraft
    {
        return match ($data->type) {
            SpacecraftType::Probe => new Probe($data->slug, $data->name, $data->fuel),
            SpacecraftType::Crewed => $this->loadCargoHold(new CrewedShip($data->slug, $data->name, $data->fuel), $data),
            SpacecraftType::Cargo => $this->loadCargoHold(new CargoHauler($data->slug, $data->name, $data->fuel), $data),
        };
    }

    private function loadCargoHold(CrewedShip|CargoHauler $spacecraft, SpacecraftData $data): CrewedShip|CargoHauler
    {
        $spacecraft->cargoCapacityInTons = $data->cargoCapacityInTons;

        return $spacecraft;
    }
}
