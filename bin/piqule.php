<?php

declare(strict_types=1);

use Haspadar\Piqule\CommandLine;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Source\DiskSources;
use Haspadar\Piqule\Target\Command\Synchronization;
use Haspadar\Piqule\Target\Command\WithDryRunNotice;
use Haspadar\Piqule\Target\Storage\DiskTargetStorage;
use Haspadar\Piqule\Target\Storage\DryRunTargetStorage;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Console();

try {
    $projectRoot = Composer\InstalledVersions::getRootPackage()['install_path']
        ?: throw new PiquleException('Cannot determine project root');

    $libraryRoot = Composer\InstalledVersions::getInstallPath('haspadar/piqule')
        ?: throw new PiquleException('Cannot determine piqule install path');

    $sources = new DiskSources($libraryRoot . '/templates');
    $targetStorage = new DiskTargetStorage($projectRoot);

    $cli = new CommandLine($argv);
    match ($cli->command()) {
        'sync' => (new Synchronization($sources, $targetStorage, $output))->run(),
        'sync --dry-run' => (new WithDryRunNotice(
            new Synchronization($sources, new DryRunTargetStorage($targetStorage), $output),
            $output,
        ))->run(),
        default => throw new PiquleException(
            sprintf('Unknown command: "%s"', $cli->command()),
        ),
    };
} catch (PiquleException $e) {
    $output->write(new Error($e->getMessage()));
    exit(1);
}
