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
    public function extractsNumericDefaultFromBlock(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'timeout.php',
                    '/* @placeholder TIMEOUT */30/* @endplaceholder */',
                ),
            ),
            new HasPlaceholders([
                '/* @placeholder TIMEOUT */30/* @endplaceholder */' => '30',
            ]),
        );
    }

    #[Test]
    public function extractsBooleanDefaultFromBlock(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'flags.php',
                    '/* @placeholder ALLOW_UNSUPPORTED */true/* @endplaceholder */',
                ),
            ),
            new HasPlaceholders([
                '/* @placeholder ALLOW_UNSUPPORTED */true/* @endplaceholder */' => 'true',
            ]),
        );
    }

    #[Test]
    public function extractsStringDefaultFromBlock(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'path.php',
                    "/* @placeholder BASE_PATH */'../app'/* @endplaceholder */",
                ),
            ),
            new HasPlaceholders([
                "/* @placeholder BASE_PATH */'../app'/* @endplaceholder */" => "'../app'",
            ]),
        );
    }

    #[Test]
    public function extractsMultiplePlaceholderBlocksFromSameFile(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'multiple.php',
                    '/* @placeholder FIRST */true/* @endplaceholder */'
                    . '/* @placeholder SECOND */42/* @endplaceholder */',
                ),
            ),
            new HasPlaceholders([
                '/* @placeholder FIRST */true/* @endplaceholder */' => 'true',
                '/* @placeholder SECOND */42/* @endplaceholder */' => '42',
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

    #[Test]
    public function extractsMultilinePlaceholderBlock(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'complex.php',
                    '
                    /* @placeholder CONFIG */
                    [
                        "a" => true,
                        "b" => false,
                    ]
                    /* @endplaceholder */
                    ',
                ),
            ),
            new HasPlaceholders([
                '/* @placeholder CONFIG */
                    [
                        "a" => true,
                        "b" => false,
                    ]
                    /* @endplaceholder */'
                => '[
                        "a" => true,
                        "b" => false,
                    ]',
            ]),
        );
    }
}
