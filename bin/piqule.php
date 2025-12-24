<?php

declare(strict_types=1);

use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\RunContext;
use Haspadar\Piqule\Source\DiskSources;
use Haspadar\Piqule\Target\Command\Synchronization;
use Haspadar\Piqule\Target\DiskTargetStorage;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $context = new RunContext($argv);
    $root = $context->root();
    $sources = new DiskSources(dirname(__DIR__) . '/templates');
    $targetStorage = new DiskTargetStorage($root);

    match ($context->command()) {
        'sync' => (new Synchronization($sources, $targetStorage, $output))->run(),
        default => throw new PiquleException(
            sprintf('Unknown command: %s', $context->command()),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
