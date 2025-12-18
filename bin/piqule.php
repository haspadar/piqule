<?php

declare(strict_types=1);

use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\InitializedProject;
use Haspadar\Piqule\Project\ProjectOf;
use Haspadar\Piqule\Project\Sentinel;
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
    $sourceDirectory = new DiskSourceDirectory(dirname(__DIR__) . '/templates');
    $targetDirectory = new DiskTargetDirectory($context->root());
    $project = new ProjectOf(
        new Sentinel($context->root()),
        new InitializedProject($sourceDirectory, $targetDirectory),
        new UninitializedProject($sourceDirectory, $targetDirectory),
    );

    match ($context->command()) {
        'init' => $project->init(new InitMaterialization($output)),
        'update' => $project->update(new UpdateMaterialization($output)),
        default => throw new PiquleException(
            sprintf('Unknown command: %s', $context->command()),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
