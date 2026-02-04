<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Output;

use Haspadar\Piqule\Output\Line\Line;
use Haspadar\Piqule\Output\Output;

final class FakeOutput implements Output
{
    /** @var list<Line> */
    private array $lines = [];

    public function write(Line $line): void
    {
        $this->lines[] = $line;
    }

    /**
     * @return list<Line>
     */
    public function lines(): array
    {
        return $this->lines;
    }
}
