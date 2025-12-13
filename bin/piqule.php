<?php

declare(strict_types=1);

use Haspadar\Piqule\Command\Init;
use Haspadar\Piqule\Command\Update;
use Haspadar\Piqule\FileSystem\DiskFileSystem;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\Structure\Root;
use Haspadar\Piqule\Structure\Templates;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$argument = $argv[1] ?? '';
$root = getenv('COMPOSER_CWD') ?: getcwd();
if ($root === false) {
    (new Console())->write(new Error('Cannot determine working directory'));
    exit(1);
}

$templates = dirname(__DIR__) . '/templates';

switch ($argument) {
    case 'init':
        (new Init(
            $templates,
            $root,
            new DiskFileSystem(),
            new Console()))->run();
        break;

    case 'update':
        (new Update(
            $templates,
            $root,
            new DiskFileSystem(),
            new Console()))->run();
        exit(1);

    case '':
        (new Console())->write(new Error('Usage: piqule <init|update>'));
        exit(1);

    default:
        (new Console())->write(new Error("Unknown command: $argument"));
        exit(1);
}
