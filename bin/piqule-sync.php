#!/usr/bin/env php
<?php

declare(strict_types=1);

use Haspadar\Piqule\Options;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Source\DiskSources;
use Haspadar\Piqule\Target\Command\Synchronization;
use Haspadar\Piqule\Target\Command\WithDryRunNotice;
use Haspadar\Piqule\Target\Storage\DiskTargetStorage;
use Haspadar\Piqule\Target\Storage\DryRunTargetStorage;

foreach ([__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php'] as $file) {
    if (file_exists($file)) {
        require $file;
        break;
    }
}

$output = new Console();

try {
    $projectRoot = getcwd();
    if ($projectRoot === false) {
        throw new PiquleException('Cannot determine current working directory');
    }

    $libraryRoot = Composer\InstalledVersions::getInstallPath('haspadar/piqule')
            ?: throw new PiquleException('Cannot determine piqule install path');

    $sources = new DiskSources($libraryRoot . '/templates');
    $targetStorage = new DiskTargetStorage($projectRoot);
    $options = new Options($argv);
    if ($options->isDryRun()) {
        $targetStorage = new DryRunTargetStorage($targetStorage);
    }

    $command = new Synchronization($sources, $targetStorage, $output);
    $options->isDryRun()
            ? (new WithDryRunNotice($command, $output))->run()
            : $command->run();
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
