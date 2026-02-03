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
    public function prefixesFileName(): void
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
}
