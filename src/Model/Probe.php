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

    /**
     * Small and unmanned, so it travels far on very little.
     */
    #[Override]
    public function millionKmPerFuelUnit(): int
    {
        return 10;
    }
}
