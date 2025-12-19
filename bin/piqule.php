<?php

declare(strict_types=1);

use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\InitializedProject;
use Haspadar\Piqule\Project\Lock\JsonLock;
use Haspadar\Piqule\Project\PiquleDirectory;
use Haspadar\Piqule\Project\ProjectOf;
use Haspadar\Piqule\Project\UninitializedProject;
use Haspadar\Piqule\RunContext;
use Haspadar\Piqule\Source\DiskSourceDirectory;
use Haspadar\Piqule\Target\DiskTargetDirectory;
use Haspadar\Piqule\Target\Materialization\InitMaterialization;
use Haspadar\Piqule\Target\Materialization\UpdateMaterialization;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $context = new RunContext($argv);
    $root = $context->root();
    $piqule = new PiquleDirectory($root . '/.piqule');
    $sourceDirectory = new DiskSourceDirectory(
        dirname(__DIR__) . '/templates',
    );
    $targetDirectory = new DiskTargetDirectory($root);
    $project = new ProjectOf(
        $piqule,
        new InitializedProject($sourceDirectory, $targetDirectory),
        new UninitializedProject($sourceDirectory, $targetDirectory),
    );

    match ($context->command()) {
        'init' => $project->init(
            new InitMaterialization($output),
            new JsonLock($piqule->lockFile()),
        ),
        'update' => $project->update(
            new UpdateMaterialization($output),
            new JsonLock($piqule->lockFile()),
        ),
        default => throw new PiquleException(
            sprintf('Unknown command: %s', $context->command()),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
