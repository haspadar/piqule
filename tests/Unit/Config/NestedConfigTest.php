<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\NestedConfig;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

final class NestedConfigTest extends TestCase
{
    #[Test]
    public function wrapsScalarIntoList(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'threshold' => 80,
            ],
        ]);

        self::assertSame(
            [80],
            $config->values('phpmetrics.threshold'),
        );
    }

    #[Test]
    public function returnsListForExistingPath(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'includes' => ['src', 'app'],
            ],
        ]);

        self::assertSame(
            ['src', 'app'],
            $config->values('phpmetrics.includes'),
        );
    }

    #[Test]
    public function returnsEmptyArrayForMissingPath(): void
    {
        $config = new NestedConfig([]);

        self::assertSame(
            [],
            $config->values('phpmetrics.missing'),
        );
    }

    #[Test]
    public function throwsWhenValueIsObject(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'invalid' => new stdClass(),
            ],
        ]);

        $this->expectException(PiquleException::class);

        $config->values('phpmetrics.invalid');
    }

    #[Test]
    public function throwsWhenListIsAssociative(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'includes' => [
                    'src' => true,
                ],
            ],
        ]);

        $this->expectException(PiquleException::class);

        $config->values('phpmetrics.includes');
    }

    #[Test]
    public function throwsWhenListContainsNonScalar(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'includes' => ['src', new stdClass()],
            ],
        ]);

        $this->expectException(PiquleException::class);

        $config->values('phpmetrics.includes');
    }

    #[Test]
    public function wrapsBooleanScalarIntoList(): void
    {
        $config = new NestedConfig([
            'feature' => [
                'enabled' => true,
            ],
        ]);

        self::assertSame(
            [true],
            $config->values('feature.enabled'),
        );
    }

    #[Test]
    public function wrapsStringScalarIntoList(): void
    {
        $config = new NestedConfig([
            'app' => [
                'name' => 'piqule',
            ],
        ]);

        self::assertSame(
            ['piqule'],
            $config->values('app.name'),
        );
    }
}
