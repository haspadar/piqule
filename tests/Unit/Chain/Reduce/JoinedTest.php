<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Chain\Reduce;

use Haspadar\Piqule\Chain\Reduce\Joined;
use Haspadar\Piqule\Chain\Render\Neon\NeonBool;
use Haspadar\Piqule\Settings\Value\BoolValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JoinedTest extends TestCase
{
    #[Test]
    public function concatenatesRenderedPartsWithGlue(): void
    {
        self::assertSame(
            'true, false',
            (new Joined([
                new NeonBool(new BoolValue(true)),
                new NeonBool(new BoolValue(false)),
            ], ', '))->rendered(),
            'Joined must concatenate rendered outputs separated by the glue string',
        );
    }

    #[Test]
    public function omitsGlueForSingleElementList(): void
    {
        self::assertSame(
            'true',
            (new Joined([new NeonBool(new BoolValue(true))], ', '))->rendered(),
            'Joined must not insert the glue when there is only one part to render',
        );
    }

    #[Test]
    public function returnsEmptyStringForEmptyPartsList(): void
    {
        self::assertSame(
            '',
            (new Joined([], ', '))->rendered(),
            'Joined must return an empty string when there are no parts to join',
        );
    }
}
