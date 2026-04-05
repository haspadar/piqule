<?php

declare(strict_types=1);

$autoload = file_exists(__DIR__ . '/../vendor/autoload.php')
    ? __DIR__ . '/../vendor/autoload.php'
    : __DIR__ . '/../../../autoload.php';

if (!is_file($autoload)) {
    fwrite(STDERR, "Cannot locate Composer autoload.php\n");
    exit(1);
}

require_once $autoload;
