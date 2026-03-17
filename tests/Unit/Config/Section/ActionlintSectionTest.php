<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\ActionlintSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ActionlintSectionTest extends TestCase
{
    #[Test]
    public function enablesActionlintByDefault(): void
    {
        self::assertSame(
            [true],
            [(new ActionlintSection())->toArray()['actionlint.enabled']],
            'actionlint must be enabled by default',
        );
    }
}
