<?php

declare(strict_types=1);

namespace Cosmic\Console;

final readonly class Application
{
    public const int EXIT_OK = 0;
    public const int EXIT_FAILURE = 1;
    public const int EXIT_USAGE = 2;

    private const array HELP_FLAGS = ['help', '--help', '-h'];

    /**
     * @var array<string, CommandInterface> indexed by command name
     */
    private array $commands;

    public function __construct(private Output $output, CommandInterface ...$commands)
    {
        $indexed = [];
        foreach ($commands as $command) {
            $indexed[$command->name()] = $command;
        }

        $this->commands = $indexed;
    }

    /**
     * @param list<string> $argv the raw arguments, script name first
     *
     * @return int the process exit code
     */
    public function run(array $argv): int
    {
        $name = $argv[1] ?? null;

        if ($name === null || in_array($name, self::HELP_FLAGS, true)) {
            $this->printUsage();

            return self::EXIT_OK;
        }

        $command = $this->commands[$name] ?? null;

        if ($command === null) {
            $this->output->error(sprintf('Unknown command "%s".', $name));
            $this->output->error('Run "php bin/cosmic help" to see what is available.');

            return self::EXIT_USAGE;
        }

        return $command->run(array_slice($argv, 2), $this->output);
    }

    private function printUsage(): void
    {
        $this->output->line('Cosmic Mission Control');
        $this->output->line();
        $this->output->line('Usage:');
        $this->output->line('  php bin/cosmic <command> [arguments]');
        $this->output->line();
        $this->output->line('Commands:');

        foreach ($this->commands as $command) {
            $this->output->line(sprintf('  %-30s %s', $command->usage(), $command->description()));
        }
    }
}
