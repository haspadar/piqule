<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use InvalidArgumentException;
use Override;

final readonly class JoinAction implements Action
{
    private string $delimiterValue;

    public function __construct(
        Args $delimiter,
    ) {
        $values = $delimiter->values();

        if (count($values) !== 1) {
            throw new InvalidArgumentException(
                sprintf(
                    'Join delimiter must contain exactly one value, got %d',
                    count($values),
                ),
            );
        }

        $this->delimiterValue = (string) $values[0];
    }

    #[Override]
    public function transformed(Args $args): Args
    {
        $items = $args->values();

        if ($items === []) {
            return new ListArgs(['']);
        }

        return new ListArgs([
            implode($this->delimiterValue, $items),
        ]);
    }
}
