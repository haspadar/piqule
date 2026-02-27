<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use Haspadar\Piqule\PiquleException;
use Override;

final readonly class DefaultScalarAction implements Action
{
    public function __construct(private string $raw) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        $values = $args->values();

        if ($values === []) {
            return new ListArgs([
                $this->defaultValue(),
            ]);
        }

        if (count($values) !== 1) {
            throw new PiquleException(
                'Cannot default scalar: more than one value',
            );
        }

        return $args;
    }

    private function defaultValue(): string
    {
        $values = (new UnquotedArgs(
            new ListArgs([$this->raw]),
        ))->values();

        return (string) ($values[0] ?? '');
    }
}
