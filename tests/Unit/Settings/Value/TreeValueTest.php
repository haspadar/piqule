<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings\Value;

use Haspadar\Piqule\Settings\Value\BoolValue;
use Haspadar\Piqule\Settings\Value\TreeValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TreeValueTest extends TestCase
{
    #[Test]
    public function exposesNestedEntries(): void
    {
        $entries = ['ignoreAbstract' => new BoolValue(true)];

        self::assertSame(
            $entries,
            (new TreeValue($entries))->entries,
            'TreeValue must expose its key-to-value map through the entries property',
        );
    }
}
