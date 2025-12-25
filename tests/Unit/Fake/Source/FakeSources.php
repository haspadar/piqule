<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Source;

use Haspadar\Piqule\Source\Source;
use Haspadar\Piqule\Source\Sources;

final class FakeSources implements Sources
{
    /** @var list<Source> */
    private array $files;

    /**
     * @param list<Source> $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function files(): iterable
    {
        return $this->files;
    }
}
