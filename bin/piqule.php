<?php

declare(strict_types=1);

use Haspadar\Piqule\Command\Init;
use Haspadar\Piqule\Command\Update;
use Haspadar\Piqule\FileSystem\DiskFileSystem;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\Structure\Root;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();
if (!isset($argv[1])) {
    $output->write(new Error('Usage: piqule <init|update>'));
    exit(1);
}

$argument = $argv[1];
$root = new Root(getenv('COMPOSER_CWD') ?: getcwd());
match ($argument) {
    'init' => (new Init($root, new DiskFileSystem(), $output))->run(),
    'update' => (new Update($root, new DiskFileSystem(), $output))->run(),
    default => $output->write(new Error("Unknown command: $argument\n"))
};
