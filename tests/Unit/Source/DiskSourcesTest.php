<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source;

use Haspadar\Piqule\Source\DiskSources;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskSourcesTest extends TestCase
{
    #[Test]
    public function throwsExceptionWhenDirectoryDoesNotExist(): void
    {
        $this->expectExceptionMessage('Directory does not exist: "/no/such/dir"');

        (new DiskSources('/no/such/dir'))->files()->current();
    }
}
