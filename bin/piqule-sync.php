#!/usr/bin/env php
<?php

declare(strict_types=1);

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\File\PrefixedFile;
use Haspadar\Piqule\Files\CombinedFiles;
use Haspadar\Piqule\Files\EachFile;
use Haspadar\Piqule\Files\FolderFiles;
use Haspadar\Piqule\Files\MappedFiles;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\DiffingStorage;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Storage\Reaction\ReportingStorageReaction;
use Haspadar\Piqule\Storage\Reaction\StorageReactions;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $projectRoot = getcwd()
        ?: throw new PiquleException('Cannot determine current working directory');

    $libraryRoot = Composer\InstalledVersions::getInstallPath('haspadar/piqule')
        ?: throw new PiquleException('Cannot determine piqule install path');
    $output = new Console();
    $files = new CombinedFiles([
        new FolderFiles(
            new DiskStorage($libraryRoot . '/templates/always'),
            '',
        ),
        new MappedFiles(
            new FolderFiles(
                new DiskStorage($libraryRoot . '/templates/git'),
                '',
            ),
            fn(File $file): File => new PrefixedFile('.git', $file),
        ),
    ]);

    $storage = new DiffingStorage(
        new DiskStorage($projectRoot),
        new StorageReactions([
            new ReportingStorageReaction($output),
        ]),
    );

    (new EachFile($files, fn(File $file) => $storage->write($file)))->run();
} catch (PiquleException $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
