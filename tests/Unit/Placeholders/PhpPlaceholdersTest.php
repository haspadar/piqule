<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Placeholders;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Placeholders\PhpPlaceholders;
use Haspadar\Piqule\Tests\Constraint\Placeholders\HasPlaceholders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpPlaceholdersTest extends TestCase
{
    #[Test]
    public function extractsNumericDefault(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'timeout.php',
                    '/* @placeholder TIMEOUT default(30) */',
                ),
            ),
            new HasPlaceholders([
                '/* @placeholder TIMEOUT default(30) */' => '30',
            ]),
        );
    }

    #[Test]
    public function extractsBooleanDefault(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'flags.php',
                    '/* @placeholder ALLOW_UNSUPPORTED default(true) */',
                ),
            ),
            new HasPlaceholders([
                '/* @placeholder ALLOW_UNSUPPORTED default(true) */' => 'true',
            ]),
        );
    }

    #[Test]
    public function extractsStringDefault(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'path.php',
                    "/* @placeholder BASE_PATH default('../app') */",
                ),
            ),
            new HasPlaceholders([
                "/* @placeholder BASE_PATH default('../app') */" => "'../app'",
            ]),
        );
    }

    #[Test]
    public function extractsMultiplePlaceholdersFromSameFile(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'multiple.php',
                    '
                    /* @placeholder FIRST default(true) */
                    /* @placeholder SECOND default(42) */
                    ',
                ),
            ),
            new HasPlaceholders([
                '/* @placeholder FIRST default(true) */' => 'true',
                '/* @placeholder SECOND default(42) */' => '42',
            ]),
        );
    }

    #[Test]
    public function ignoresNonPlaceholderComments(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'noop.php',
                    '/* just a regular comment */',
                ),
            ),
            new HasPlaceholders([]),
        );
    }
}
