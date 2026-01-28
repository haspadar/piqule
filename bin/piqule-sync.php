#!/usr/bin/env php
<?php

declare(strict_types=1);

use Haspadar\Piqule\Application\AnnouncedApplication;
use Haspadar\Piqule\Application\FileApplication;
use Haspadar\Piqule\File\CompositeFiles;
use Haspadar\Piqule\File\File;
use Haspadar\Piqule\File\ForcedFile;
use Haspadar\Piqule\File\InitialFile;
use Haspadar\Piqule\File\MappedFiles;
use Haspadar\Piqule\File\Reaction\FileReactions;
use Haspadar\Piqule\File\Reaction\ReportingFileReaction;
use Haspadar\Piqule\File\StorageFiles;
use Haspadar\Piqule\Options;
use Haspadar\Piqule\Output\Color\Yellow;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\DiskPath;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Storage\DryRunStorage;

$autoloaded = false;
foreach ([__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php'] as $file) {
    if (file_exists($file)) {
        require_once $file;
        $autoloaded = true;
        break;
    }
}

if (!$autoloaded) {
    fwrite(STDERR, "Error: Composer autoloader not found. Run 'composer install' first.\n");
    exit(1);
}

try {
    $projectRoot = getcwd();
    if ($projectRoot === false) {
        throw new PiquleException('Cannot determine current working directory');
    }

    $libraryRoot = Composer\InstalledVersions::getInstallPath('haspadar/piqule')
        ?: throw new PiquleException('Cannot determine piqule install path');

    $output = new Console();
    $reactions = new FileReactions([
        new ReportingFileReaction(
            $output,
        ),
    ]);

    $files = new CompositeFiles([
        new MappedFiles(
            new StorageFiles(
                new DiskStorage(
                    new DiskPath($libraryRoot . '/templates/once')
                ),
            ),
            fn(File $file): File => new InitialFile($file),
        ),
        new MappedFiles(
            new StorageFiles(
                new DiskStorage(
                    new DiskPath($libraryRoot . '/templates/always')
                ),
            ),
            fn(File $file): File => new ForcedFile($file),
        ),
    ]);

    $targetStorage = new DiskStorage(new DiskPath($projectRoot));
    $application = (new Options($argv))->isDryRun()
        ? new AnnouncedApplication(
            new FileApplication(
                $files,
                new DryRunStorage($targetStorage),
                $reactions,
            ),
            $output,
            new Text("DRY RUN — no files were written\n", new Yellow()),
            new Text("\nDRY RUN completed", new Yellow()),
        )
        : new FileApplication(
            $files,
            $targetStorage,
            $reactions,
        );

    $application->run();
} catch (PiquleException $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    fwrite(STDERR, $e->getTraceAsString() . PHP_EOL);
    exit(1);
}
