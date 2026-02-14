<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Config\NestedConfig;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsValues;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfigActionTest extends TestCase
{
    #[Test]
    public function returnsListValuesFromConfig(): void
    {
        self::assertThat(
            (new ConfigAction(
                new NestedConfig([
                    'phpmetrics' => [
                        'include' => ['mbstring', 'intl'],
                    ],
                ]),
                'phpmetrics.include',
            ))->transformed(new ListArgs([])),
            new HasArgsValues(['mbstring', 'intl']),
        );
    }

    #[Test]
    public function stringifiesBooleanValuesFromConfig(): void
    {
        self::assertThat(
            (new ConfigAction(
                new NestedConfig([
                    'feature' => [
                        'flags' => [true, false],
                    ],
                ]),
                'feature.flags',
            ))->transformed(new ListArgs([])),
            new HasArgsValues(['true', 'false']),
        );
    }

    #[Test]
    public function returnsEmptyListWhenConfigValueMissing(): void
    {
        self::assertThat(
            (new ConfigAction(
                new NestedConfig([]),
                'missing.key',
            ))->transformed(new ListArgs([])),
            new HasArgsValues([]),
        );
    }
}
