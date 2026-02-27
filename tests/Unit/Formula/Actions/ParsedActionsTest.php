<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Actions;

use Haspadar\Piqule\Formula\Action\DefaultListAction;
use Haspadar\Piqule\Formula\Action\FormatEachAction;
use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Actions\ParsedActions;
use Haspadar\Piqule\Tests\Constraint\Formula\Actions\HasActionNames;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ParsedActionsTest extends TestCase
{
    #[Test]
    public function returnsEmptyListWhenExpressionHasNoActions(): void
    {
        self::assertThat(
            new ParsedActions('', []),
            new HasActionNames([]),
        );
    }

    #[Test]
    public function parsesSingleAction(): void
    {
        self::assertThat(
            new ParsedActions(
                'format("x=%s")',
                [
                    'format' => fn(string $raw) => new FormatEachAction($raw),
                ],
            ),
            new HasActionNames([
                FormatEachAction::class,
            ]),
        );
    }

    #[Test]
    public function preservesActionOrder(): void
    {
        self::assertThat(
            new ParsedActions(
                'default(["x"])|join(",")',
                [
                    'default' => fn(string $raw) => new DefaultListAction($raw),
                    'join' => fn(string $raw) => new JoinAction($raw),
                ],
            ),
            new HasActionNames([
                DefaultListAction::class,
                JoinAction::class,
            ]),
        );
    }
}
