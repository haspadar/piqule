<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\DefaultListAction;
use Haspadar\Piqule\Formula\Args\ListParsedArgs;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsList;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultListActionTest extends TestCase
{
    #[Test]
    public function keepsOriginalListWhenItIsNotEmpty(): void
    {
        self::assertThat(
            (new DefaultListAction(
                new ListParsedArgs(new RawArgs('[blue]')),
            ))->apply(
                new ListParsedArgs(new RawArgs('[red,green]')),
            ),
            new HasArgsList(['red', 'green']),
        );
    }

    #[Test]
    public function returnsDefaultListWhenOriginalListIsEmpty(): void
    {
        self::assertThat(
            (new DefaultListAction(
                new ListParsedArgs(new RawArgs('[fallback]')),
            ))->apply(
                new ListParsedArgs(new RawArgs('[]')),
            ),
            new HasArgsList(['fallback']),
        );
    }
}
