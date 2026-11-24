<?php

declare(strict_types=1);

namespace Cosmic\Console;

final readonly class Application
{
    public const int EXIT_OK = 0;
    public const int EXIT_FAILURE = 1;
    public const int EXIT_USAGE = 2;

    private const array HELP_FLAGS = ['help', '--help', '-h'];

    public function __construct(private Output $output)
    {
    }

    /**
     * @param list<string> $argv the raw arguments, script name first
     *
     * @return int the process exit code
     */
    public function run(array $argv): int
    {
        $command = $argv[1] ?? null;

        if ($command === null || in_array($command, self::HELP_FLAGS, true)) {
            $this->printUsage();

            return self::EXIT_OK;
        }

        $this->output->error(sprintf('Unknown command "%s".', $command));
        $this->output->error('Run "php bin/cosmic help" to see what is available.');

        return self::EXIT_USAGE;
    }

    private function printUsage(): void
    {
        $this->output->line('Cosmic Mission Control');
        $this->output->line();
        $this->output->line('Usage:');
        $this->output->line('  php bin/cosmic <command> [arguments]');
        $this->output->line();
        $this->output->line('No commands are available yet.');
    }
}
