<?php

declare(strict_types=1);

use Haspadar\Piqule\FileSystem\DiskSourceDirectory;
use Haspadar\Piqule\FileSystem\DiskTargetDirectory;
use Haspadar\Piqule\Init;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\Step\End;
use Haspadar\Piqule\Step\MissingTarget;
use Haspadar\Piqule\Update;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$argument = $argv[1] ?? '';
$root = getenv('COMPOSER_CWD') ?: getcwd();
$output = new Console();
if ($root === false) {
    $output->write(new Error('Cannot determine working directory'));
    exit(1);
}

$templates = dirname(__DIR__) . '/templates';

switch ($argument) {
    case 'init':
        (new Init(
            new DiskSourceDirectory($templates),
            new DiskTargetDirectory($root),
            new MissingTarget(
                $output,
                new End($output),
            ),
        ))->run();
        break;

    case 'update':
        (new Update($output))->run();
        exit(1);

    case '':
        $output->write(new Error('Usage: piqule <init|update>'));
        exit(1);

    default:
        $output->write(new Error("Unknown command: $argument"));
        exit(1);
}
