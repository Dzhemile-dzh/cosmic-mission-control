<?php

declare(strict_types=1);

namespace Cosmic\Tests\Console;

use Cosmic\Console\Application;
use Cosmic\Console\FleetCommand;
use Cosmic\Console\LaunchCommand;
use Cosmic\Console\Output;
use Cosmic\Console\PlanetsCommand;
use Cosmic\Model\SpacecraftData;
use Cosmic\Model\SpacecraftType;
use Cosmic\Service\FleetLoader;
use Cosmic\Service\MissionControl;
use Cosmic\Tests\Support\InMemoryFleetStorage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Runs the whole application against in-memory streams and an in-memory fleet.
 */
final class ApplicationTest extends TestCase
{
    /** @var resource */
    private mixed $stdout;

    /** @var resource */
    private mixed $stderr;

    private Application $application;

    protected function setUp(): void
    {
        $this->stdout = fopen('php://memory', 'w+');
        $this->stderr = fopen('php://memory', 'w+');

        $fleetLoader = new FleetLoader(new InMemoryFleetStorage(
            new SpacecraftData('voyager', 'Voyager', SpacecraftType::Probe, 60),
            new SpacecraftData('pioneer', 'Pioneer', SpacecraftType::Probe, 5),
        ));

        $this->application = new Application(
            new Output($this->stdout, $this->stderr),
            new FleetCommand($fleetLoader),
            new PlanetsCommand(),
            new LaunchCommand($fleetLoader, new MissionControl()),
        );
    }

    /**
     * @return iterable<string, array{list<string>, int, string, string}>
     */
    public static function commandLines(): iterable
    {
        // arguments, exit code, expected on stdout, expected on stderr
        yield 'no command shows help' => [[], Application::EXIT_OK, 'launch <spacecraft> <planet>', ''];
        yield 'help' => [['--help'], Application::EXIT_OK, 'Commands:', ''];
        yield 'fleet' => [['fleet'], Application::EXIT_OK, 'Voyager', ''];
        yield 'planets' => [['planets'], Application::EXIT_OK, 'Saturn', ''];
        yield 'successful launch' => [['launch', 'voyager', 'mars'], Application::EXIT_OK, 'Voyager reached Mars', ''];
        yield 'aborted launch' => [['launch', 'pioneer', 'mars'], Application::EXIT_FAILURE, 'Mission aborted', ''];
        yield 'unknown command' => [['warp'], Application::EXIT_USAGE, '', 'Unknown command "warp"'];
        yield 'missing arguments' => [['launch'], Application::EXIT_USAGE, '', 'Missing argument "spacecraft"'];
        yield 'missing planet' => [['launch', 'voyager'], Application::EXIT_USAGE, '', 'Missing argument "planet"'];
        yield 'unknown spacecraft' => [['launch', 'voyagr', 'mars'], Application::EXIT_FAILURE, '', '"voyagr"'];
        yield 'unknown planet' => [['launch', 'voyager', 'pluto'], Application::EXIT_FAILURE, '', 'Unknown destination "pluto"'];
    }

    /**
     * @param list<string> $arguments
     */
    #[DataProvider('commandLines')]
    public function testRun(array $arguments, int $expectedExitCode, string $expectedStdout, string $expectedStderr): void
    {
        $exitCode = $this->application->run(['bin/cosmic', ...$arguments]);

        self::assertSame($expectedExitCode, $exitCode);
        $this->assertStreamMatches($expectedStdout, $this->stdout, 'stdout');
        $this->assertStreamMatches($expectedStderr, $this->stderr, 'stderr');
    }

    /**
     * An empty expectation means the stream must be empty.
     *
     * @param resource $stream
     */
    private function assertStreamMatches(string $expected, mixed $stream, string $name): void
    {
        rewind($stream);
        $actual = stream_get_contents($stream);

        if ($expected === '') {
            self::assertSame('', $actual, $name.' should be empty');
        } else {
            self::assertStringContainsString($expected, $actual, $name);
        }
    }
}
