<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Model\AbstractSpacecraft;
use Cosmic\Model\MissionReport;
use Cosmic\Model\Planet;

final readonly class MissionControl
{
    /**
     * Sends the spacecraft to the planet, burning the fuel the trip needs.
     * A destination out of range is an expected outcome, not an error: the
     * launch is called off and the spacecraft keeps its fuel.
     */
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
