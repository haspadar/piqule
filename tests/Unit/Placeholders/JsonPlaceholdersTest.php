<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Placeholders;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Placeholders\JsonPlaceholders;
use Haspadar\Piqule\Tests\Constraint\Placeholders\HasPlaceholders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JsonPlaceholdersTest extends TestCase
{
    #[Test]
    public function extractsNumericDefault(): void
    {
        self::assertThat(
            new JsonPlaceholders(
                new TextFile(
                    'timeout.json',
                    '{"value":{"$placeholder":"TIMEOUT","default":30}}',
                ),
            ),
            new HasPlaceholders([
                '{"$placeholder":"TIMEOUT","default":30}' => '30',
            ]),
        );
    }

    #[Test]
    public function extractsStringDefault(): void
    {
        self::assertThat(
            new JsonPlaceholders(
                new TextFile(
                    'path.json',
                    '{"value":{"$placeholder":"PATH","default":"../app"}}',
                ),
            ),
            new HasPlaceholders([
                '{"$placeholder":"PATH","default":"../app"}' => '"../app"',
            ]),
        );
    }

    #[Test]
    public function extractsBooleanDefault(): void
    {
        self::assertThat(
            new JsonPlaceholders(
                new TextFile(
                    'flags.json',
                    '{"value":{"$placeholder":"ENABLED","default":false}}',
                ),
            ),
            new HasPlaceholders([
                '{"$placeholder":"ENABLED","default":false}' => 'false',
            ]),
        );
    }

    #[Test]
    public function extractsMultiplePlaceholdersFromSameFile(): void
    {
        self::assertThat(
            new JsonPlaceholders(
                new TextFile(
                    'multi.json',
                    '{"first":{"$placeholder":"A","default":1},"second":{"$placeholder":"B","default":["x","y"]}}',
                ),
            ),
            new HasPlaceholders([
                '{"$placeholder":"A","default":1}' => '1',
                '{"$placeholder":"B","default":["x","y"]}' => '["x","y"]',
            ]),
        );
    }

    #[Test]
    public function extractsArrayDefaultFromMultilineJson(): void
    {
        self::assertThat(
            new JsonPlaceholders(
                new TextFile(
                    'config.json',
                    '{
                        "value": {
                            "$placeholder": "DIRS",
                            "default": ["src"]
                        }
                    }',
                ),
            ),
            new HasPlaceholders([
                '{
                            "$placeholder": "DIRS",
                            "default": ["src"]
                        }' => '["src"]',
            ]),
        );
    }
}
