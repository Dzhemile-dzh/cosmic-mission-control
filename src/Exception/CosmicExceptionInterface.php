<?php

declare(strict_types=1);

namespace Cosmic\Exception;

use Throwable;

/**
 * Marks every exception this application throws on purpose, so callers can
 * catch them all at once without also swallowing genuine bugs.
 */
interface CosmicExceptionInterface extends Throwable
{
}
