#!/usr/bin/env php
<?php

declare(strict_types=1);

use Haspadar\Piqule\Config\NestedConfig;
use Haspadar\Piqule\File\ConfiguredFile;
use Haspadar\Piqule\File\File;
use Haspadar\Piqule\File\PrefixedFile;
use Haspadar\Piqule\Files\CombinedFiles;
use Haspadar\Piqule\Files\EachFile;
use Haspadar\Piqule\Files\FolderFiles;
use Haspadar\Piqule\Files\MappedFiles;
use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Action\DefaultAction;
use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Action\ScalarAction;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\DiffingStorage;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Storage\Reaction\ReportingStorageReaction;
use Haspadar\Piqule\Storage\Reaction\StorageReactions;

require_once __DIR__ . '/../vendor/autoload.php';

$output = new Console();

try {
    $projectRoot = getcwd()
        ?: throw new PiquleException('Cannot determine current working directory');

    $libraryRoot = Composer\InstalledVersions::getInstallPath('haspadar/piqule')
        ?: throw new PiquleException('Cannot determine piqule install path');

    $projectConfigData = file_exists($projectRoot . '/.piqule/config.php')
        ? require $projectRoot . '/.piqule/config.php'
        : [];
    if (!is_array($projectConfigData)) {
        throw new PiquleException(
            '.piqule/config.php must return array',
        );
    }

    $config = new NestedConfig($projectConfigData);

    $files = new CombinedFiles([
        new MappedFiles(
            new FolderFiles(
                new DiskStorage($libraryRoot . '/templates/root'),
                '',
            ),
            fn(File $file): File => new ConfiguredFile($file, [
                'config' => fn(string $raw): Action => new ConfigAction($config, $raw),
                'default' => fn(string $raw): Action => new DefaultAction($raw),
                'format' => fn(string $raw): Action => new FormatAction($raw),
                'join' => fn(string $raw): Action => new JoinAction($raw),
                'scalar' => fn(string $raw): Action => new ScalarAction(),
            ], ),
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
    $output->write(
        new Haspadar\Piqule\Output\Line\Error($e->getMessage()),
    );
    exit(1);
}
