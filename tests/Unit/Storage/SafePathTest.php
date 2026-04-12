<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\Storage\SafePath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SafePathTest extends TestCase
{
    #[Test]
    public function normalizesBackslashesToForwardSlashes(): void
    {
        self::assertSame(
            '/root/a/b/c',
            (new SafePath('/root'))->resolve('a\\b\\c'),
            'SafePath must normalize backslashes to forward slashes',
        );
    }

    #[Test]
    public function stripsTrailingSlashFromRoot(): void
    {
        self::assertSame(
            '/root/file.txt',
            (new SafePath('/root/'))->resolve('file.txt'),
            'SafePath must strip trailing slash from root before joining',
        );
    }
}
