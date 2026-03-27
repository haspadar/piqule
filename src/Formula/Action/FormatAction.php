<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use Haspadar\Piqule\PiquleException;
use Override;
use Throwable;

/**
 * Applies a sprintf template to a single incoming value
 */
final readonly class FormatAction implements Action
{
    public function __construct(
        private string $raw,
    ) {}

    /**
     * @throws PiquleException
     */
    #[Override]
    public function transformed(Args $args): Args
    {
        $values = $args->values();

        if ($values === []) {
            throw new PiquleException(
                'Cannot format empty value',
            );
        }

        if (count($values) > 1) {
            throw new PiquleException(
                'Cannot format list: expected single value',
            );
        }

        $templateArgs = new UnquotedArgs(new ListArgs([$this->raw]));
        $templateValues = $templateArgs->values();
        $template = (string) ($templateValues[0] ?? '');

        $scalar = (new StringifiedArgs($args))->values()[0];

        try {
            $result = sprintf($template, $scalar);
        } catch (Throwable $e) {
            throw new PiquleException(
                sprintf('format() failed: %s', $e->getMessage()),
                previous: $e,
            );
        }

        return new ListArgs([$result]);
    }
}
