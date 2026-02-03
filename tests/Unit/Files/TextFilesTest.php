<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Files;

use Haspadar\Piqule\Files\TextFiles;
use Haspadar\Piqule\Tests\Constraint\Files\HasFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TextFilesTest extends TestCase
{
    #[Test]
    public function exposesAllTextFiles(): void
    {
        self::assertThat(
            new TextFiles([
                'README.md' => 'Piqule',
                'config/app.ini' => 'name=piqule',
            ]),
            new HasFiles([
                'README.md' => 'Piqule',
                'config/app.ini' => 'name=piqule',
            ]),
        );
    }
}
