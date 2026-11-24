<?php

declare(strict_types=1);

namespace Cosmic\Model;

enum SpacecraftType: string
{
    case Probe = 'probe';
    case Crewed = 'crewed';
    case Cargo = 'cargo';
}
