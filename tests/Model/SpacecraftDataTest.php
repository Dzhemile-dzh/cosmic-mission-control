<?php

declare(strict_types=1);

namespace Cosmic\Tests\Model;

use Cosmic\Exception\InvalidFleetDataException;
use Cosmic\Model\SpacecraftData;
use Cosmic\Model\SpacecraftType;
use PHPUnit\Framework\TestCase;

final class SpacecraftDataTest extends TestCase
{
    public function testARawRowIsCastToTheRightTypes(): void
    {
        $data = SpacecraftData::fromArray([
            'slug' => 'atlas',
            'name' => 'Atlas',
            'type' => 'cargo',
            'fuel' => '60',
            'cargo_capacity' => '500',
        ]);

        self::assertSame('atlas', $data->slug);
        self::assertSame(SpacecraftType::Cargo, $data->type);
        self::assertSame(60, $data->fuel);
        self::assertSame(500, $data->cargoCapacityInTons);
    }

    public function testCargoCapacityIsOptional(): void
    {
        $data = SpacecraftData::fromArray(['slug' => 'v', 'name' => 'V', 'type' => 'probe', 'fuel' => 60]);

        self::assertSame(0, $data->cargoCapacityInTons);
    }

    public function testAMissingFieldIsReported(): void
    {
        $this->expectException(InvalidFleetDataException::class);
        $this->expectExceptionMessage('"fuel"');

        SpacecraftData::fromArray(['slug' => 'v', 'name' => 'V', 'type' => 'probe']);
    }

    public function testAnUnknownTypeIsReported(): void
    {
        $this->expectException(InvalidFleetDataException::class);
        $this->expectExceptionMessage('"ufo"');

        SpacecraftData::fromArray(['slug' => 'v', 'name' => 'V', 'type' => 'ufo', 'fuel' => 60]);
    }
}
