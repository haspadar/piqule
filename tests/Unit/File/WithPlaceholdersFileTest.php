<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\File\WithPlaceholdersFile;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Haspadar\Piqule\Tests\Fake\Placeholders\FakePlaceholders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithPlaceholdersFileTest extends TestCase
{
    #[Test]
    public function replacesPlaceholderUsingProvidedPlaceholders(): void
    {
        self::assertSame(
            'listen: 8080',
            (new WithPlaceholdersFile(
                new TextFile(
                    'nginx.conf',
                    'listen: {{ PORT }}',
                ),
                new FakePlaceholders([
                    new DefaultPlaceholder(
                        '{{ PORT }}',
                        '8080',
                    ),
                ]),
            ))->contents(),
            'WithPlaceholdersFile must apply provided placeholders',
        );
    }

    #[Test]
    public function keepsOriginalFileName(): void
    {
        self::assertSame(
            'docker-compose.override.yml',
            (new WithPlaceholdersFile(
                new TextFile(
                    'docker-compose.override.yml',
                    'services: {}',
                ),
                new FakePlaceholders([]),
            ))->name(),
            'WithPlaceholdersFile must keep original file name',
        );
    }
}
