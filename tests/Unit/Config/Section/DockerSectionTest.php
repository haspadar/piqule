<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\DockerSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DockerSectionTest extends TestCase
{
    #[Test]
    public function declaresDockerImageWithPinnedDigest(): void
    {
        $image = (new DockerSection())->toArray()['docker.image'];

        self::assertMatchesRegularExpression(
            '/^ghcr\.io\/haspadar\/piqule-infra@sha256:[a-f0-9]{64}$/',
            (string) $image,
            'docker.image must be a pinned digest reference',
        );
    }
}
