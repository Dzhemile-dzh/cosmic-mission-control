<?php

declare(strict_types=1);

namespace Cosmic\Console;

use Cosmic\Exception\CosmicExceptionInterface;

interface CommandInterface
{
    /**
     * The word the user types to run this command, e.g. "launch".
     */
    public function name(): string;

    /**
     * The name plus its arguments, e.g. "launch <spacecraft> <planet>".
     */
    public function usage(): string;

    public function description(): string;

    /**
     * @param list<string> $arguments everything typed after the command name
     *
     * @return int the process exit code
     *
     * @throws CosmicExceptionInterface
     */
    public function run(array $arguments, Output $output): int;
}
