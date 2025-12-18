<?php

declare(strict_types=1);

use Haspadar\Piqule\FileSystem\DiskSourceDirectory;
use Haspadar\Piqule\FileSystem\DiskTargetDirectory;
use Haspadar\Piqule\Init;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\RunContext;
use Haspadar\Piqule\Step\End;
use Haspadar\Piqule\Step\MissingTarget;
use Haspadar\Piqule\Update;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $run = new RunContext($argv);

    $templates = dirname(__DIR__) . '/templates';
    $root = $run->root();

    match ($run->argument(1)) {
        'init' => (new Init(
            new DiskSourceDirectory($templates),
            new DiskTargetDirectory($root),
            new MissingTarget(
                $output,
                new End($output),
            ),
        ))->run(),

        'update' => (new Update($output))->run(),

        default => throw new PiquleException(
            sprintf('Unknown command: %s', $run->argument(1)),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
