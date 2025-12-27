<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Source\DiskSources;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskSourcesTest extends TestCase
{
    #[Test]
    public function throwsExceptionWhenDirectoryDoesNotExist(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskSources('__piqule__non_existent_dir__'))->files()->current();
    }
}
