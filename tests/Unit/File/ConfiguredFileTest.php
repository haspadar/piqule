<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\OverrideConfig;
use Haspadar\Piqule\File\ConfiguredFile;
use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Constraint\Files\HasFileContents;
use Haspadar\Piqule\Tests\Constraint\HasFormulaError;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfiguredFileTest extends TestCase
{
    #[Test]
    public function replacesPlaceholderUsingPipeline(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            ['php.versions' => ['8.3', '8.4']],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'file',
                    '<< config(php.versions)|format_each("%s")|join(",") >>',
                ),
                $config,
            ),
            new HasFileContents('8.3,8.4'),
            'placeholder must resolve to joined matrix values',
        );
    }

    #[Test]
    public function leavesFileUntouchedWhenNoPlaceholdersPresent(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            [],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'plain.txt',
                    "just text\nno placeholders here",
                ),
                $config,
            ),
            new HasFileContents("just text\nno placeholders here"),
            'file without placeholders must be returned unchanged',
        );
    }

    #[Test]
    public function resolvesDotSeparatedConfigKey(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            ['coverage.patch.target' => 85],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'config.yaml',
                    'coverage: << config(coverage.patch.target)|format("%s%%") >>',
                ),
                $config,
            ),
            new HasFileContents('coverage: 85%'),
        );
    }

    #[Test]
    public function usesDefaultValueWhenKeyNotOverridden(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            [],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'broken.yaml',
                    'value: << config(shellcheck.shell)|join("") >>',
                ),
                $config,
            ),
            new HasFileContents('value: bash'),
        );
    }

    #[Test]
    public function wrapsUnknownActionWithFileContext(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            [],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'broken.yaml',
                    '<< unknown(a) >>',
                ),
                $config,
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
        $config = new OverrideConfig(
            new DefaultConfig(),
            ['php.versions' => ['8.3', '8.4']],
        );

        $this->expectException(PiquleException::class);

        (new ConfiguredFile(
            new TextFile(
                'file',
                '<< config(php.versions) >>',
            ),
            $config,
        ))->contents();
    }

    #[Test]
    public function throwsWhenFirstActionReceivesArguments(): void
    {
        $this->expectException(PiquleException::class);

        (new ConfiguredFile(
            new TextFile(
                'file',
                '<< config(shellcheck.shell)|first(something) >>',
            ),
            new OverrideConfig(new DefaultConfig(), []),
        ))->contents();
    }

    #[Test]
    public function rendersContentWhenIfNotEmptyReceivesNonEmptyInput(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            ['php.versions' => ['8.3', '8.4']],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'file',
                    '<< config(php.versions)|format_each("%s")|join(",")|if_not_empty()|format("[%s]") >>',
                ),
                $config,
            ),
            new HasFileContents('[8.3,8.4]'),
            'if_not_empty must pass non-empty value through to format',
        );
    }

    #[Test]
    public function rendersEmptyWhenIfNotEmptyReceivesEmptyInput(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            ['psalm.project.files' => []],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'file',
                    'before|<< config(psalm.project.files)|format_each("%s")|join(",")|if_not_empty() >>|after',
                ),
                $config,
            ),
            new HasFileContents('before||after'),
            'if_not_empty must produce empty string for empty config list',
        );
    }

    #[Test]
    public function rendersContentWhenIfEmptyReceivesEmptyInput(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            ['psalm.project.files' => []],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'file',
                    '<< config(psalm.project.files)|join(",")|if_empty()|format("none") >>',
                ),
                $config,
            ),
            new HasFileContents('none'),
            'if_empty must pass empty value through to format',
        );
    }

    #[Test]
    public function rendersEmptyWhenIfEmptyReceivesNonEmptyInput(): void
    {
        $config = new OverrideConfig(
            new DefaultConfig(),
            ['php.versions' => ['8.3']],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'file',
                    'x<< config(php.versions)|join(",")|if_empty() >>y',
                ),
                $config,
            ),
            new HasFileContents('xy'),
            'if_empty must produce empty string for non-empty config list',
        );
    }

    #[Test]
    public function throwsWhenIfNotEmptyReceivesArguments(): void
    {
        $this->expectException(PiquleException::class);

        (new ConfiguredFile(
            new TextFile(
                'file',
                '<< config(shellcheck.shell)|if_not_empty(something) >>',
            ),
            new OverrideConfig(new DefaultConfig(), []),
        ))->contents();
    }

    #[Test]
    public function throwsWhenIfEmptyReceivesArguments(): void
    {
        $this->expectException(PiquleException::class);

        (new ConfiguredFile(
            new TextFile(
                'file',
                '<< config(shellcheck.shell)|if_empty(something) >>',
            ),
            new OverrideConfig(new DefaultConfig(), []),
        ))->contents();
    }

    #[Test]
    public function preservesOriginMode(): void
    {
        $file = new ConfiguredFile(
            new TextFile(
                'file',
                '<< config(shellcheck.shell)|join("") >>',
                0o755,
            ),
            new OverrideConfig(new DefaultConfig(), []),
        );

        self::assertSame(0o755, $file->mode(), 'ConfiguredFile must preserve the origin file mode');
    }
}
