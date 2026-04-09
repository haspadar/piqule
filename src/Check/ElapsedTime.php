<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

/**
 * Human-readable elapsed time formatting.
 */
final readonly class ElapsedTime
{
    /** Initializes with elapsed seconds. */
    public function __construct(private float $seconds) {}

    /** Formats as "1.2s" or "2m05s". */
    public function formatted(): string
    {
        $rounded = round($this->seconds, 1);

        if ($rounded < 60) {
            return sprintf('%.1fs', $rounded);
        }

        $total = (int) round($this->seconds);

        return sprintf('%dm%02ds', intdiv($total, 60), $total % 60);
    }
}
