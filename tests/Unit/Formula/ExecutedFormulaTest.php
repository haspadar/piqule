<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula;

use Haspadar\Piqule\Config\NestedConfig;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Action\DefaultAction;
use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Actions\ParsedActions;
use Haspadar\Piqule\Formula\ExecutedFormula;
use Haspadar\Piqule\Formula\NormalizedFormula;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Constraint\Formula\HasFormulaResult;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ExecutedFormulaTest extends TestCase
{
    private function actions(string $expression, NestedConfig $config): ParsedActions
    {
        return new ParsedActions(
            (new NormalizedFormula($expression))->result(),
            [
                'config' => fn(string $raw) => new ConfigAction($config, $raw),
                'default' => fn(string $raw) => new DefaultAction($raw),
                'format' => fn(string $raw) => new FormatAction($raw),
                'join' => fn(string $raw) => new JoinAction($raw),
            ],
        );
    }

    #[Test]
    public function formatsAndJoinsList(): void
    {
        $config = new NestedConfig([
            'a' => ['x', 'y'],
        ]);

        self::assertThat(
            new ExecutedFormula(
                $this->actions(
                    'config(a)|default(["x"])|format("v=%s")|join(",")',
                    $config,
                ),
            ),
            new HasFormulaResult('v=x,v=y'),
        );
    }

    #[Test]
    public function appliesDefaultWhenMissing(): void
    {
        self::assertThat(
            new ExecutedFormula(
                $this->actions(
                    'config(missing)|default(["fallback"])|join(",")',
                    new NestedConfig([]),
                ),
            ),
            new HasFormulaResult('fallback'),
        );
    }

    #[Test]
    public function handlesBooleanValue(): void
    {
        $config = new NestedConfig([
            'flag' => true,
        ]);

        self::assertThat(
            new ExecutedFormula(
                $this->actions(
                    'config(flag)|default([false])|format("flag=%s")|join("")',
                    $config,
                ),
            ),
            new HasFormulaResult('flag=true'),
        );
    }

    #[Test]
    public function throwsWhenFormulaDoesNotReduceToSingleValue(): void
    {
        $this->expectException(PiquleException::class);

        $config = new NestedConfig([
            'a' => ['x', 'y'],
        ]);

        (new ExecutedFormula(
            $this->actions(
                'config(a)|default(["x"])',
                $config,
            ),
        ))->result();
    }

    #[Test]
    public function returnsEmptyStringWhenNoActionsProduceValues(): void
    {
        self::assertThat(
            new ExecutedFormula(
                $this->actions(
                    'format("%s")',
                    new NestedConfig([]),
                ),
            ),
            new HasFormulaResult(''),
        );
    }
}
