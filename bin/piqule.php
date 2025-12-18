<?php

declare(strict_types=1);

use Haspadar\Piqule\Init;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\PiquleSentinel;
use Haspadar\Piqule\RunContext;
use Haspadar\Piqule\Source\DiskSourceDirectory;
use Haspadar\Piqule\Step\End;
use Haspadar\Piqule\Step\MissingTarget;
use Haspadar\Piqule\Target\DiskTargetDirectory;
use Haspadar\Piqule\Update;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $context = new RunContext($argv);

    $templates = dirname(__DIR__) . '/templates';
    $root = $context->root();

    $sentinel = new PiquleSentinel($context->root());

    match ($context->argument(1)) {
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
            sprintf('Unknown command: %s', $context->argument(1)),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
