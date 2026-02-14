<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\Config\NestedConfig;
use Haspadar\Piqule\File\ConfiguredFile;
use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Action\DefaultAction;
use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Action\ScalarAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Constraint\Files\HasFileContents;
use Haspadar\Piqule\Tests\Constraint\HasFormulaError;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfiguredFileTest extends TestCase
{
    private function actions(NestedConfig $config): array
    {
        return [
            'config' => fn(string $raw): Action => new ConfigAction($config, $raw),
            'default' => fn(string $raw): Action => new DefaultAction($raw),
            'format' => fn(string $raw): Action => new FormatAction($raw),
            'join' => fn(string $raw): Action => new JoinAction($raw),
            'scalar' => fn(string $raw): Action => new ScalarAction(),
        ];
    }

    #[Test]
    public function replacesPlaceholderUsingPipeline(): void
    {
        $config = new NestedConfig([]);

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'file',
                    '<< config(a)|default(["x","y"])|format("%s")|join(",") >>',
                ),
                $this->actions($config),
            ),
            new HasFileContents('x,y'),
        );
    }

    #[Test]
    public function leavesFileUntouchedWhenNoPlaceholdersPresent(): void
    {
        $config = new NestedConfig([]);

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'plain.txt',
                    "just text\nno placeholders here",
                ),
                $this->actions($config),
            ),
            new HasFileContents("just text\nno placeholders here"),
        );
    }

    #[Test]
    public function supportsNestedConfigKeys(): void
    {
        $config = new NestedConfig([
            'coverage' => [
                'range' => '80...100',
            ],
        ]);

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'config.yaml',
                    'coverage: << config(coverage.range)|default([""] )|join("") >>',
                ),
                $this->actions($config),
            ),
            new HasFileContents('coverage: 80...100'),
        );
    }

    #[Test]
    public function returnsEmptyStringWhenMissingAndNoDefault(): void
    {
        $config = new NestedConfig([]);

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'broken.yaml',
                    'value: << config(missing.key)|join(",") >>',
                ),
                $this->actions($config),
            ),
            new HasFileContents('value: '),
        );
    }

    #[Test]
    public function wrapsUnknownActionWithFileContext(): void
    {
        $config = new NestedConfig([]);

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'broken.yaml',
                    '<< unknown(a) >>',
                ),
                $this->actions($config),
            ),
            new HasFormulaError(
                'broken.yaml',
                'unknown(a)',
                'Unknown formula action',
            ),
        );
    }

    #[Test]
    public function throwsWhenFormulaProducesMultipleValues(): void
    {
        $config = new NestedConfig([
            'a' => ['x', 'y'],
        ]);

        $this->expectException(PiquleException::class);

        (new ConfiguredFile(
            new TextFile(
                'file',
                '<< config(a)|default(["x"]) >>',
            ),
            $this->actions($config),
        ))->contents();
    }

    #[Test]
    public function formatsUsingEmptyTemplate(): void
    {
        $result = (new FormatAction('""'))
            ->transformed(new ListArgs(['a']));

        self::assertSame([''], $result->values());
    }

    #[Test]
    public function joinsWithEmptyDelimiter(): void
    {
        $result = (new JoinAction(''))
            ->transformed(new ListArgs(['a', 'b']));

        self::assertSame(['ab'], $result->values());
    }
}
