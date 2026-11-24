<?php

declare(strict_types=1);

namespace Cosmic\Model;

final readonly class MissionReport
{
    public function __construct(
        public AbstractSpacecraft $spacecraft,
        public Planet $destination,
        public int $fuelBurned,
    ) {
    }
}
