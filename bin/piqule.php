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
use Haspadar\Piqule\Step\End;
use Haspadar\Piqule\Step\MissingTarget;
use Haspadar\Piqule\Target\DiskTargetDirectory;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $context = new RunContext($argv);
    $sourceDirectory = new DiskSourceDirectory(dirname(__DIR__) . '/templates');
    $targetDirectory = new DiskTargetDirectory($context->root());
    $project = new ProjectOf(
        new Sentinel($context->root()),
        new InitializedProject($output),
        new UninitializedProject(
            $sourceDirectory,
            $targetDirectory,
            new MissingTarget(
                $output,
                new End($output),
            ),
        ),
    );

    match ($context->argument(1)) {
        'init' => $project->init(),
        'update' => $project->update(),
        default => throw new PiquleException(
            sprintf('Unknown command: %s', $context->argument(1)),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
