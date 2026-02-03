<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\ReplacedFile;
use Haspadar\Piqule\File\TextFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReplacedFileTest extends TestCase
{
    #[Test]
    public function replacesContents(): void
    {
        self::assertSame(
            'version=1.2.3',
            (new ReplacedFile(
                new TextFile(
                    'config/app.ini',
                    'version={{version}}',
                ),
                '{{version}}',
                '1.2.3',
            ))->contents(),
        );
    }

    #[Test]
    public function delegatesName(): void
    {
        self::assertSame(
            'config/app.ini',
            (new ReplacedFile(
                new TextFile(
                    'config/app.ini',
                    'x',
                ),
                'x',
                'y',
            ))->name(),
        );
    }
}
