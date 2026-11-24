<?php

declare(strict_types=1);

namespace Cosmic\Model;

/**
 * Which kind of spacecraft a stored row describes.
 */
enum SpacecraftType: string
{
    case Probe = 'probe';
    case Crewed = 'crewed';
    case Cargo = 'cargo';
}
