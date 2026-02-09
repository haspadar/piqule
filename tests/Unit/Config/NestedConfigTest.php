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
    public function returnsScalarValueForExistingPath(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'threshold' => 80,
            ],
        ]);

        self::assertSame(
            80,
            $config->value('phpmetrics.threshold')->value(),
            'NestedConfig did not return scalar value for existing path',
        );
    }

    #[Test]
    public function returnsListValueForExistingPath(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'includes' => ['src', 'app'],
            ],
        ]);

        self::assertSame(
            ['src', 'app'],
            $config->value('phpmetrics.includes')->value(),
            'NestedConfig did not return list value for existing path',
        );
    }

    #[Test]
    public function throwsExceptionWhenPathIsMissing(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [],
        ]);

        $this->expectException(PiquleException::class);

        $config->value('phpmetrics.includes')->value();
    }

    #[Test]
    public function throwsExceptionWhenValueIsNotScalarOrList(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'invalid' => new stdClass(),
            ],
        ]);

        $this->expectException(PiquleException::class);

        $config->value('phpmetrics.invalid');
    }

    #[Test]
    public function throwsExceptionWhenListIsAssociative(): void
    {
        $config = new NestedConfig([
            'phpmetrics' => [
                'includes' => [
                    'src' => true,
                ],
            ],
        ]);

        $this->expectException(PiquleException::class);

        $config->value('phpmetrics.includes')->value();
    }
}
