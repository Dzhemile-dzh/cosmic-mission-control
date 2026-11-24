<?php

declare(strict_types=1);

namespace Cosmic\Console;

use Cosmic\Exception\CosmicExceptionInterface;

interface CommandInterface
{
    public function name(): string;
    public function usage(): string;
    public function description(): string;

    /**
     * @param array $arguments
     * @param Output $output
     * @return int
     *
     * @throws CosmicExceptionInterface
     */
    public function run(array $arguments, Output $output): int;
}
