<?php

declare(strict_types=1);

namespace Cosmic\Service;

use Cosmic\Exception\InsufficientFuelException;
use Cosmic\Model\AbstractSpacecraft;
use Cosmic\Model\MissionReport;
use Cosmic\Model\Planet;

final readonly class MissionControl
{
    /**
     * @throws InsufficientFuelException when the planet is out of range
     */
    public function launch(AbstractSpacecraft $spacecraft, Planet $destination): MissionReport
    {
        $fuelNeeded = $spacecraft->fuelNeededFor($destination);
        $spacecraft->burnFuel($fuelNeeded);

        return new MissionReport($spacecraft, $destination, $fuelNeeded);
    }
}
