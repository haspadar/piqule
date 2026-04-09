<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Haspadar\Piqule\Output\Output;

/**
 * Formats check execution progress to output.
 */
final readonly class CheckReport
{
    private const int TITLE_WIDTH = 20;

    /** Initializes with output channel and total check count. */
    public function __construct(private Output $output, private int $total) {}

    /** Reports a check starting. */
    public function started(string $name, int $number): void
    {
        $this->output->muted(
            $this->total > 1
                ? sprintf('[RUN]  %-' . self::TITLE_WIDTH . 's', $name)
                    . sprintf('%5s', "{$number}/{$this->total}")
                : "[RUN]  {$name}",
        );
    }

    /** Reports a check that passed. */
    public function passed(string $name, float $elapsed): void
    {
        $this->output->success(
            sprintf('[OK]   %-' . self::TITLE_WIDTH . 's', $name)
                . sprintf('%5s', (new ElapsedTime($elapsed))->formatted()),
        );
    }

    /** Reports a check that failed. */
    public function failed(string $name, float $elapsed): void
    {
        $this->output->error(
            sprintf('[FAIL] %-' . self::TITLE_WIDTH . 's', $name)
                . sprintf('%5s', (new ElapsedTime($elapsed))->formatted()),
        );
    }
}
