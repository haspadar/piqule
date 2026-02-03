<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\PrefixedFile;
use Haspadar\Piqule\File\TextFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PrefixedFileTest extends TestCase
{
    #[Test]
    public function keepsFileContentsUnchanged(): void
    {
        self::assertSame(
            '{"enabled":true}',
            (new PrefixedFile(
                '.env',
                new TextFile(
                    'vars/app.settings.json',
                    '{"enabled":true}',
                ),
            ))->contents(),
            'File contents must not be modified',
        );
    }

    #[Test]
    public function prefixesFileNameWithoutExtraSlashes(): void
    {
        self::assertSame(
            '.config/tools/setup.sh',
            (new PrefixedFile(
                '.config',
                new TextFile(
                    'tools/setup.sh',
                    '#!/bin/sh',
                ),
            ))->name(),
            'File name must be prefixed',
        );
    }

    #[Test]
    public function trimsTrailingSlashFromPrefix(): void
    {
        self::assertSame(
            '.config/bin/install.sh',
            (new PrefixedFile(
                '.config/',
                new TextFile(
                    'bin/install.sh',
                    '#!/bin/sh',
                ),
            ))->name(),
            'Trailing slash in prefix must be ignored',
        );
    }

    #[Test]
    public function trimsLeadingSlashFromFileName(): void
    {
        self::assertSame(
            '.env/runtime/app.env',
            (new PrefixedFile(
                '.env',
                new TextFile(
                    '/runtime/app.env',
                    'KEY=value',
                ),
            ))->name(),
            'Leading slash in file name must be ignored',
        );
    }
}
