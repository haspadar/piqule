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
            ['ci.php.matrix' => ['8.3', '8.4']],
        );

        self::assertThat(
            new ConfiguredFile(
                new TextFile(
                    'file',
                    '<< config(ci.php.matrix)|format_each("%s")|join(",") >>',
                ),
                $config,
            ),
            new HasFileContents('8.3,8.4'),
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
        );
    }

    #[Test]
    public function supportsDotSeparatedConfigKeys(): void
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
            ['ci.php.matrix' => ['8.3', '8.4']],
        );

        $this->expectException(PiquleException::class);

        (new ConfiguredFile(
            new TextFile(
                'file',
                '<< config(ci.php.matrix) >>',
            ),
            $config,
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

        self::assertSame(0o755, $file->mode());
    }
}
