<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\FlatConfig;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

final class FlatConfigTest extends TestCase
{
    #[Test]
    public function wrapsScalarIntoList(): void
    {
        $config = new FlatConfig([
            'phpmetrics.threshold' => 80,
        ]);

        self::assertSame(
            [80],
            $config->values('phpmetrics.threshold'),
        );
    }

    #[Test]
    public function returnsListWhenValueIsList(): void
    {
        $config = new FlatConfig([
            'phpmetrics.includes' => ['src', 'app'],
        ]);

        self::assertSame(
            ['src', 'app'],
            $config->values('phpmetrics.includes'),
        );
    }

    #[Test]
    public function returnsEmptyArrayForMissingKey(): void
    {
        $config = new FlatConfig([]);

        self::assertSame(
            [],
            $config->values('unknown.key'),
        );
    }

    #[Test]
    public function throwsWhenValueIsObject(): void
    {
        $config = new FlatConfig([
            'invalid.key' => new stdClass(),
        ]);

        $this->expectException(PiquleException::class);

        $config->values('invalid.key');
    }

    #[Test]
    public function throwsWhenValueIsAssociativeArray(): void
    {
        $config = new FlatConfig([
            'logging.channels' => [
                'main' => 'stdout',
            ],
        ]);

        $this->expectException(PiquleException::class);

        $config->values('logging.channels');
    }

    #[Test]
    public function throwsWhenListContainsNonScalar(): void
    {
        $config = new FlatConfig([
            'build.targets' => [
                'cli',
                new stdClass(),
            ],
        ]);

        $this->expectException(PiquleException::class);

        $config->values('build.targets');
    }
}
