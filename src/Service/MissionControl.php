<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Model\AbstractSpacecraft;
use Cosmic\Model\MissionReport;
use Cosmic\Model\Planet;

final readonly class MissionControl
{
    public function launch(AbstractSpacecraft $spacecraft, Planet $destination): MissionReport
    {
        if (!$spacecraft->canReach($destination)) {
            return MissionReport::aborted($spacecraft, $destination, sprintf(
                '%s is %d million km away, but %s can only travel %d.',
                $destination->name,
                $destination->distanceInMillionKm(),
                $spacecraft->name,
                $spacecraft->rangeInMillionKm()
            ));
        }

        $fuelNeeded = $spacecraft->fuelNeededFor($destination);
        $spacecraft->burnFuel($fuelNeeded);

        return MissionReport::success($spacecraft, $destination, $fuelNeeded);
    }
}
