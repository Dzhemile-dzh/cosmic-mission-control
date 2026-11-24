<?php

declare(strict_types=1);

namespace Cosmic\Model;

final readonly class MissionReport
{
    private function __construct(
        public MissionStatus $status,
        public AbstractSpacecraft $spacecraft,
        public Planet $destination,
        public int $fuelBurned = 0,
        public ?string $reason = null,
    ) {
    }

    public static function success(AbstractSpacecraft $spacecraft, Planet $destination, int $fuelBurned): self
    {
        return new self(MissionStatus::Success, $spacecraft, $destination, $fuelBurned);
    }

    public static function aborted(AbstractSpacecraft $spacecraft, Planet $destination, string $reason): self
    {
        return new self(MissionStatus::Aborted, $spacecraft, $destination, reason: $reason);
    }

    public function isSuccess(): bool
    {
        return $this->status === MissionStatus::Success;
    }
}
