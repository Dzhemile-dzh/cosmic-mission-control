<?php

declare(strict_types=1);

namespace Cosmic\Model;

use Override;

final class Probe extends AbstractSpacecraft
{
    #[Override]
    public function getType(): string
    {
        return 'Probe';
    }
    #[Override]
    public function millionKmPerFuelUnit(): int
    {
        return 10;
    }
}
