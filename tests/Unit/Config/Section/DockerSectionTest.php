<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\DockerSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DockerSectionTest extends TestCase
{
    #[Test]
    public function declaresDockerImageKey(): void
    {
        self::assertArrayHasKey(
            'docker.image',
            (new DockerSection())->toArray(),
            'DockerSection must declare docker.image key',
        );
    }
}
