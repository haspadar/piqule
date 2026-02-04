<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Placeholders;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Placeholders\FilePlaceholders;
use Haspadar\Piqule\Tests\Constraint\Placeholders\HasPlaceholders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FilePlaceholdersTest extends TestCase
{
    #[Test]
    public function extractsUnquotedDefault(): void
    {
        self::assertThat(
            new FilePlaceholders(
                new TextFile(
                    'server.yml',
                    'port: {{ PORT | default(8080) }}',
                ),
            ),
            new HasPlaceholders([
                '{{ PORT | default(8080) }}' => '8080',
            ]),
        );
    }

    #[Test]
    public function removesSingleQuotesFromDefault(): void
    {
        self::assertThat(
            new FilePlaceholders(
                new TextFile(
                    'app.yml',
                    "mode: {{ MODE | default('strict') }}",
                ),
            ),
            new HasPlaceholders([
                "{{ MODE | default('strict') }}" => 'strict',
            ]),
        );
    }

    #[Test]
    public function removesDoubleQuotesFromDefault(): void
    {
        self::assertThat(
            new FilePlaceholders(
                new TextFile(
                    'log.yml',
                    'level: {{ LEVEL | default("warn") }}',
                ),
            ),
            new HasPlaceholders([
                '{{ LEVEL | default("warn") }}' => 'warn',
            ]),
        );
    }

    #[Test]
    public function keepsBooleanDefaultAsString(): void
    {
        self::assertThat(
            new FilePlaceholders(
                new TextFile(
                    'features.yml',
                    'debug: {{ DEBUG | default(false) }}',
                ),
            ),
            new HasPlaceholders([
                '{{ DEBUG | default(false) }}' => 'false',
            ]),
        );
    }

    #[Test]
    public function extractsMultiplePlaceholdersFromSameFile(): void
    {
        self::assertThat(
            new FilePlaceholders(
                new TextFile(
                    'application.yml',
                    'port: {{ PORT | default(8080) }}, mode: {{ MODE | default("prod") }}, debug: {{ DEBUG | default(false) }}',
                ),
            ),
            new HasPlaceholders([
                '{{ PORT | default(8080) }}' => '8080',
                '{{ MODE | default("prod") }}' => 'prod',
                '{{ DEBUG | default(false) }}' => 'false',
            ]),
        );
    }
}
