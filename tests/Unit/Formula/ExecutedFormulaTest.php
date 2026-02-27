<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula;

use Haspadar\Piqule\Config\FlatConfig;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Action\DefaultListAction;
use Haspadar\Piqule\Formula\Action\FormatEachAction;
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
    private function actions(string $expression, FlatConfig $config): ParsedActions
    {
        return new ParsedActions(
            (new NormalizedFormula($expression))->result(),
            [
                'config' => fn(string $raw) => new ConfigAction($config, $raw),
                'default_list' => fn(string $raw) => new DefaultListAction($raw),
                'format_each' => fn(string $raw) => new FormatEachAction($raw),
                'join' => fn(string $raw) => new JoinAction($raw),
            ],
        );
    }

    #[Test]
    public function formatsAndJoinsList(): void
    {
        $config = new FlatConfig([
            'a' => ['x', 'y'],
        ]);

        self::assertThat(
            new ExecutedFormula(
                $this->actions(
                    'config(a)|default_list(["x"])|format_each("v=%s")|join(",")',
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
                    'config(missing)|default_list(["fallback"])|join(",")',
                    new FlatConfig([]),
                ),
            ),
            new HasFormulaResult('fallback'),
        );
    }

    #[Test]
    public function handlesBooleanValue(): void
    {
        $config = new FlatConfig([
            'flag' => true,
        ]);

        self::assertThat(
            new ExecutedFormula(
                $this->actions(
                    'config(flag)|default_list([false])|format_each("flag=%s")|join("")',
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

        $config = new FlatConfig([
            'a' => ['x', 'y'],
        ]);

        (new ExecutedFormula(
            $this->actions(
                'config(a)|default_list(["x"])',
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
                    'format_each("%s")',
                    new FlatConfig([]),
                ),
            ),
            new HasFormulaResult(''),
        );
    }
}
