<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Actions;

use Haspadar\Piqule\Formula\Action\DefaultAction;
use Haspadar\Piqule\Formula\Action\FormatAction;
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
                    'format' => fn(string $raw) => new FormatAction($raw),
                ],
            ),
            new HasActionNames([
                FormatAction::class,
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
                    'default' => fn(string $raw) => new DefaultAction($raw),
                    'join'    => fn(string $raw) => new JoinAction($raw),
                ],
            ),
            new HasActionNames([
                DefaultAction::class,
                JoinAction::class,
            ]),
        );
    }
}