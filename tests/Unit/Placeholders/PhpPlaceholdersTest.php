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
                    "setTimeout(['\$placeholder' => 'TIMEOUT', 'default' => 30]);",
                ),
            ),
            new HasPlaceholders([
                "['\$placeholder' => 'TIMEOUT', 'default' => 30]" => '30',
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
                    "setPath(['\$placeholder' => 'BASE_PATH', 'default' => '../app']);",
                ),
            ),
            new HasPlaceholders([
                "['\$placeholder' => 'BASE_PATH', 'default' => '../app']" => "'../app'",
            ]),
        );
    }

    #[Test]
    public function extractsArrayDefault(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'rules.php',
                    "setRules(['\$placeholder' => 'RULES', 'default' => ['a' => 1, 'b' => 2]]);",
                ),
            ),
            new HasPlaceholders([
                "['\$placeholder' => 'RULES', 'default' => ['a' => 1, 'b' => 2]]"
                => "['a' => 1, 'b' => 2]",
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
                    "configure(
                        ['\$placeholder' => 'FIRST', 'default' => true],
                        ['\$placeholder' => 'SECOND', 'default' => ['x', 'y']]
                    );",
                ),
            ),
            new HasPlaceholders([
                "['\$placeholder' => 'FIRST', 'default' => true]" => 'true',
                "['\$placeholder' => 'SECOND', 'default' => ['x', 'y']]"
                => "['x', 'y']",
            ]),
        );
    }

    #[Test]
    public function extractsBooleanDefaultFromMultilineArray(): void
    {
        self::assertThat(
            new PhpPlaceholders(
                new TextFile(
                    'php-cs-fixer.php',
                    "setFlag([
                        '\$placeholder' => 'ALLOW_UNSUPPORTED',
                        'default' => true,
                    ]);",
                ),
            ),
            new HasPlaceholders([
                "[
                        '\$placeholder' => 'ALLOW_UNSUPPORTED',
                        'default' => true,
                    ]" => 'true',
            ]),
        );
    }
}
