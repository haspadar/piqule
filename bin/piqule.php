<?php

declare(strict_types=1);

use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\RunContext;
use Haspadar\Piqule\Source\DiskSources;
use Haspadar\Piqule\Target\Command\Synchronization;
use Haspadar\Piqule\Target\Command\WithDryRunNotice;
use Haspadar\Piqule\Target\Storage\DiskTargetStorage;
use Haspadar\Piqule\Target\Storage\DryRunTargetStorage;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $context = new RunContext($argv);
    $sources = new DiskSources(dirname(__DIR__) . '/templates');
    match ($context->commandLine()) {
        'sync' => (new Synchronization(
            $sources,
            new DiskTargetStorage($context->root()),
            $output,
        ))->run(),
        'sync --dry-run' => (new WithDryRunNotice(
            new Synchronization(
                $sources,
                new DryRunTargetStorage(
                    new DiskTargetStorage($context->root()),
                ),
                $output,
            ),
            $output,
        ))->run(),
        default => throw new PiquleException(
            sprintf('Unknown command: "%s"', $context->commandLine()),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
