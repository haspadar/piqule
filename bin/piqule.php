<?php

declare(strict_types=1);

use Haspadar\Piqule\Command\Init;
use Haspadar\Piqule\Command\Update;
use Haspadar\Piqule\FileSystem\DiskFileSystem;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\Structure\Root;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$argument = $argv[1] ?? '';
$path = getenv('COMPOSER_CWD') ?: getcwd();
if ($path === false) {
    (new Console())->write(new Error('Cannot determine working directory'));
    exit(1);
}

$root = new Root($path);

switch ($argument) {
    case 'init':
        (new Init($root, new DiskFileSystem(), new Console()))->run();
        break;

    case 'update':
        (new Update($root, new DiskFileSystem(), new Console()))->run();
        exit(1);

    case '':
        (new Console())->write(new Error('Usage: piqule <init|update>'));
        exit(1);

    default:
        (new Console())->write(new Error("Unknown command: $argument"));
        exit(1);
}
