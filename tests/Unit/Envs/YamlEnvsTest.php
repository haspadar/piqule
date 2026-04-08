<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Envs;

use Haspadar\Piqule\Envs\YamlEnvs;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class YamlEnvsTest extends TestCase
{
    private TempFolder $folder;

    protected function setUp(): void
    {
        $this->folder = new TempFolder();
    }

    protected function tearDown(): void
    {
        $this->folder->close();
    }

    #[Test]
    public function parsesEnvsSection(): void
    {
        $path = $this->folder->withFile(
            '.piqule.yaml',
            "envs:\n  MY_VAR: \"echo hello\"\n",
        )->path() . '/.piqule.yaml';

        self::assertSame(
            ['MY_VAR' => 'echo hello'],
            (new YamlEnvs($path))->vars(),
            'YamlEnvs must parse envs section into name => command map',
        );
    }

    #[Test]
    public function parsesMultipleEnvs(): void
    {
        $path = $this->folder->withFile(
            '.piqule.yaml',
            "envs:\n  A: \"cmd-a\"\n  B: \"cmd-b\"\n",
        )->path() . '/.piqule.yaml';

        self::assertSame(
            ['A' => 'cmd-a', 'B' => 'cmd-b'],
            (new YamlEnvs($path))->vars(),
            'YamlEnvs must parse multiple envs entries',
        );
    }

    #[Test]
    public function returnsEmptyWhenNoEnvsSection(): void
    {
        $path = $this->folder->withFile(
            '.piqule.yaml',
            "override:\n  phpstan.level: 8\n",
        )->path() . '/.piqule.yaml';

        self::assertSame(
            [],
            (new YamlEnvs($path))->vars(),
            'YamlEnvs must return empty array when envs section is absent',
        );
    }

    #[Test]
    public function throwsWhenEnvsSectionIsNotMapping(): void
    {
        $this->expectException(PiquleException::class);

        $path = $this->folder->withFile(
            '.piqule.yaml',
            "envs: not-a-mapping\n",
        )->path() . '/.piqule.yaml';

        (new YamlEnvs($path))->vars();
    }

    #[Test]
    public function throwsWhenEnvsValueIsNotString(): void
    {
        $this->expectException(PiquleException::class);

        $path = $this->folder->withFile(
            '.piqule.yaml',
            "envs:\n  MY_VAR: 42\n",
        )->path() . '/.piqule.yaml';

        (new YamlEnvs($path))->vars();
    }

    #[Test]
    public function throwsWhenYamlIsMalformed(): void
    {
        $this->expectException(PiquleException::class);

        $path = $this->folder->withFile(
            '.piqule.yaml',
            "envs:\n  BROKEN: [\n",
        )->path() . '/.piqule.yaml';

        (new YamlEnvs($path))->vars();
    }
}
