<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Config\NestedConfig;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfigActionTest extends TestCase
{
    #[Test]
    public function returnsListValuesFromConfig(): void
    {
        $action = new ConfigAction(
            new NestedConfig([
                'phpmetrics' => [
                    'include' => ['mbstring', 'intl'],
                ],
            ]),
            'phpmetrics.include',
        );

        $result = $action->transformed(new ListArgs([]));

        self::assertSame(
            ['mbstring', 'intl'],
            $result->values(),
        );
    }

    #[Test]
    public function stringifiesBooleanValuesFromConfig(): void
    {
        $action = new ConfigAction(
            new NestedConfig([
                'feature' => [
                    'flags' => [true, false],
                ],
            ]),
            'feature.flags',
        );

        $result = $action->transformed(new ListArgs([]));

        self::assertSame(
            ['true', 'false'],
            $result->values(),
        );
    }

    #[Test]
    public function returnsEmptyListWhenConfigValueMissing(): void
    {
        $action = new ConfigAction(new NestedConfig([]), 'missing.key');

        $result = $action->transformed(new ListArgs([]));

        self::assertSame(
            [],
            $result->values(),
        );
    }
}
