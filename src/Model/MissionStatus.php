<?php

declare(strict_types=1);

namespace Cosmic\Model;

enum MissionStatus
{
    case Success;
    case Aborted;

    public function label(): string
    {
        return match ($this) {
            self::Success => 'Mission accomplished',
            self::Aborted => 'Mission aborted',
        };
    }
}
