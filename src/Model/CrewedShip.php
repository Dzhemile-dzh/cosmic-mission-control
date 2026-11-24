<?php

declare(strict_types=1);

namespace Cosmic\Model;

use Override;

final class CrewedShip extends AbstractSpacecraft
{
    use CarriesCargoTrait;

    #[Override]
    public function getType(): string
    {
        return 'Crewed ship';
    }

    #[Override]
    public function millionKmPerFuelUnit(): int
    {
        return 4;
    }
}
