<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Check;

use Haspadar\Piqule\Check\ConfigChecks;
use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfigChecksTest extends TestCase
{
    #[Test]
    public function yieldsCheckWhenCommandFileExists(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule/phpstan/command.sh',
            '#!/bin/bash',
        );

        $checks = new ConfigChecks(
            new FakeConfig(['phpstan.cli' => [true]]),
            $folder->path(),
        );

        $names = [];
        foreach ($checks->all() as $check) {
            $names[] = $check->name();
        }

        $folder->close();

        self::assertSame(
            ['phpstan'],
            $names,
            'ConfigChecks must yield checks with existing command files',
        );
    }

    #[Test]
    public function skipsCheckWhenCommandFileMissing(): void
    {
        $checks = new ConfigChecks(
            new FakeConfig(['phpstan.cli' => [true]]),
            '/nonexistent',
        );

        $names = [];
        foreach ($checks->all() as $check) {
            $names[] = $check->name();
        }

        self::assertSame(
            [],
            $names,
            'ConfigChecks must skip checks without command files',
        );
    }
}
