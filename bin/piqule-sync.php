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
use Haspadar\Piqule\Target\Sync\Chain;
use Haspadar\Piqule\Target\Sync\ReplaceSync;
use Haspadar\Piqule\Target\Sync\SkippingIfExistsSync;
use Haspadar\Piqule\Target\Sync\WithDryRunSync;

$autoloaded = false;
foreach ([__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php'] as $file) {
    if (file_exists($file)) {
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

    $targetStorage = new DiskTargetStorage($projectRoot);
    $cli = new Options($argv);
    $sync = new Chain([
        new SkippingIfExistsSync(
            new DiskSources($libraryRoot . '/templates/once'),
            $output,
        ),
        new ReplaceSync(
            new DiskSources($libraryRoot . '/templates/always'),
            $output,
        ),
    ]);

    if ($cli->isDryRun()) {
        (new WithDryRunSync($sync, $output))
            ->apply(new DryRunTargetStorage($targetStorage));
    } else {
        $sync->apply($targetStorage);
    }
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
