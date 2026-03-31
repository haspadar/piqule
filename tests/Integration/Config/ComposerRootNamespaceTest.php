<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Config;

use Haspadar\Piqule\Config\ComposerRootNamespace;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ComposerRootNamespaceTest extends TestCase
{
    #[Test]
    public function returnsFirstPsr4NamespaceFromComposerJson(): void
    {
        $folder = (new TempFolder())->withFile('composer.json', json_encode([
            'autoload' => ['psr-4' => ['Acme\\App\\' => 'src/']],
        ]) ?: '');

        self::assertSame(
            'Acme\\App',
            (new ComposerRootNamespace($folder->path() . '/composer.json'))->toString(),
            'ComposerRootNamespace must return the first PSR-4 namespace without trailing backslash',
        );

        $folder->close();
    }

    #[Test]
    public function returnsEmptyStringWhenComposerJsonIsMissing(): void
    {
        $folder = new TempFolder();

        self::assertSame(
            '',
            (new ComposerRootNamespace($folder->path() . '/composer.json'))->toString(),
            'ComposerRootNamespace must return empty string when composer.json does not exist',
        );

        $folder->close();
    }

    #[Test]
    public function returnsEmptyStringWhenPsr4SectionIsAbsent(): void
    {
        $folder = (new TempFolder())->withFile('composer.json', json_encode([
            'name' => 'acme/app',
        ]) ?: '');

        self::assertSame(
            '',
            (new ComposerRootNamespace($folder->path() . '/composer.json'))->toString(),
            'ComposerRootNamespace must return empty string when autoload.psr-4 is absent',
        );

        $folder->close();
    }
}
