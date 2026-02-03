<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Files;

use Haspadar\Piqule\Files\CombinedFiles;
use Haspadar\Piqule\Files\TextFiles;
use Haspadar\Piqule\Tests\Constraint\Files\HasFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CombinedFilesTest extends TestCase
{
    #[Test]
    public function combinesMultipleFileSources(): void
    {
        self::assertThat(
            new CombinedFiles([
                new TextFiles([
                    'README.md' => 'Piqule',
                ]),
                new TextFiles([
                    'config/app.ini' => 'name=piqule',
                ]),
            ]),
            new HasFiles([
                'README.md' => 'Piqule',
                'config/app.ini' => 'name=piqule',
            ]),
        );
    }
}
