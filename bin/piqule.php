<?php

declare(strict_types=1);

use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\DiskPiquleDirectory;
use Haspadar\Piqule\Project\InitializedProject;
use Haspadar\Piqule\Project\ProjectOf;
use Haspadar\Piqule\Project\Snapshot\JsonSnapshotStore;
use Haspadar\Piqule\Project\UninitializedProject;
use Haspadar\Piqule\RunContext;
use Haspadar\Piqule\Source\DiskSourceDirectory;
use Haspadar\Piqule\Target\DiskTargetDirectory;
use Haspadar\Piqule\Target\Materialization\Installation;
use Haspadar\Piqule\Target\Materialization\Synchronization;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $context = new RunContext($argv);
    $root = $context->root();
    $piqule = new DiskPiquleDirectory($root . '/.piqule');
    $sourceDirectory = new DiskSourceDirectory(dirname(__DIR__) . '/templates');
    $targetDirectory = new DiskTargetDirectory($root);
    $lockFile = new JsonSnapshotStore($root . '/piqule.lock');
    $project = new ProjectOf(
        $piqule,
        new InitializedProject($sourceDirectory, $targetDirectory, $lockFile),
        new UninitializedProject($sourceDirectory, $targetDirectory, $lockFile),
    );

    match ($context->command()) {
        'init' => $project->init(
            new Installation($output),
        ),
        'update' => $project->update(
            new Synchronization($output),
        ),
        default => throw new PiquleException(
            sprintf('Unknown command: %s', $context->command()),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
