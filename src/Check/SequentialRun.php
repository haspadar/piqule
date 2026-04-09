<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Runnable;
use Override;

/**
 * Runs checks one by one, stopping on the first failure.
 */
final readonly class SequentialRun implements Runnable
{
    /** Initializes with checks, output channel, and verbosity option. */
    public function __construct(
        private Checks $checks,
        private Output $output,
        private CliOption $verbose,
    ) {}

    #[Override]
    public function run(): void
    {
        /** @var list<Check> $all */
        $all = iterator_to_array($this->checks->all());
        $report = new CheckReport($this->output, count($all));
        $number = 0;
        $start = microtime(true);

        foreach ($all as $check) {
            $number++;
            $report->started($check->name(), $number);
            $result = (new CheckRun($check, $this->verbose))->result();

            if (!$result->passed()) {
                if ($result->output() !== '') {
                    echo $result->output() . "\n";
                }

                $report->failed($check->name(), $result->elapsed());
                $report->failed('Checks failed', microtime(true) - $start);

                throw new PiquleException('');
            }

            $report->passed($check->name(), $result->elapsed());
        }

        $report->passed('All checks passed', microtime(true) - $start);
    }
}
