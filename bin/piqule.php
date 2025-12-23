<?php

declare(strict_types=1);

use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\DiskPiquleDirectory;
use Haspadar\Piqule\Project\InitializedProject;
use Haspadar\Piqule\Project\Snapshot\JsonSnapshotStorage;
use Haspadar\Piqule\RunContext;
use Haspadar\Piqule\Source\DiskSources;
use Haspadar\Piqule\Target\DiskTargetStorage;
use Haspadar\Piqule\Target\Materialization\Synchronization;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $context = new RunContext($argv);
    $root = $context->root();
    $piqule = new DiskPiquleDirectory($root . '/.piqule');
    $sources = new DiskSources(dirname(__DIR__) . '/templates');
    $targetStorage = new DiskTargetStorage($root);
    $snapshotStorage = new JsonSnapshotStorage($root . '/piqule.lock');
    $project = new InitializedProject($sources, $targetStorage, $snapshotStorage);

    match ($context->command()) {
        'sync' => $project->sync(new Synchronization($output)),
        default => throw new PiquleException(
            sprintf('Unknown command: %s', $context->command()),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
