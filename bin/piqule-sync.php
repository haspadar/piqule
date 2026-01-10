#!/usr/bin/env php
<?php

declare(strict_types=1);

use Haspadar\Piqule\Options;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Source\DiskSources;
use Haspadar\Piqule\Target\Storage\DiskTargetStorage;
use Haspadar\Piqule\Target\Storage\DryRunTargetStorage;
use Haspadar\Piqule\Target\Sync\ReplaceSync;
use Haspadar\Piqule\Target\Sync\WithDryRunSync;

// TODO(#193): CLI bootstrap logic is duplicated between init and sync entrypoints
// This duplication is intentional for now and will be addressed by introducing a shared entrypoint

$autoloaded = false;
foreach ([__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php'] as $file) {
    if (file_exists($file)) {
        /* @SuppressWarnings("php:S2003) */
        require $file;
        $autoloaded = true;
        break;
    }
}

if (!$autoloaded) {
    fwrite(STDERR, "Error: Composer autoloader not found. Run 'composer install' first.\n");
    exit(1);
}

$output = new Console();

try {
    $projectRoot = getcwd();
    if ($projectRoot === false) {
        throw new PiquleException('Cannot determine current working directory');
    }

    $libraryRoot = Composer\InstalledVersions::getInstallPath('haspadar/piqule')
            ?: throw new PiquleException('Cannot determine piqule install path');

    $sources = new DiskSources($libraryRoot . '/templates/always');
    $targetStorage = new DiskTargetStorage($projectRoot);
    $options = new Options($argv);
    if ($options->isDryRun()) {
        $targetStorage = new DryRunTargetStorage($targetStorage);
    }

    $sync = new ReplaceSync($sources, $targetStorage, $output);
    $options->isDryRun()
        ? (new WithDryRunSync($sync, $output))->apply()
        : $sync->apply();
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
