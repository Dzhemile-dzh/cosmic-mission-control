<?php

declare(strict_types=1);

namespace Cosmic\Model;

use Override;

final class CargoHauler extends AbstractSpacecraft
{
    use CarriesCargoTrait;

    #[Override]
    public function getType(): string
    {
        return 'Cargo hauler';
    }

    #[Override]
    public function millionKmPerFuelUnit(): int
    {
        return 2;
    }
}
