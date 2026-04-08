<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use ArgumentCountError;
use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use Haspadar\Piqule\PiquleException;
use Override;
use ValueError;

/**
 * Applies a sprintf template to a single incoming value.
 */
final readonly class FormatAction implements Action
{
    private const array ESCAPE_REPLACEMENTS = [
        '\\\\' => '\\',
        '\\n' => "\n",
        '\\r' => "\r",
        '\\t' => "\t",
    ];

    /** Initializes with the raw sprintf template string. */
    public function __construct(private string $raw) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        $values = $args->values();

        if ($values === []) {
            return new ListArgs([]);
        }

        if (count($values) > 1) {
            throw new PiquleException(
                'Cannot format list: expected single value',
            );
        }

        $templateArgs = new UnquotedArgs(new ListArgs([$this->raw]));
        $templateValues = $templateArgs->values();
        $template = $this->normalize((string) ($templateValues[0] ?? ''));

        $scalar = (new StringifiedArgs($args))->values()[0] ?? '';

        try {
            $result = sprintf($template, $scalar);
        } catch (ArgumentCountError | ValueError $e) {
            throw new PiquleException(
                sprintf('format() failed: %s', $e->getMessage()),
                0,
                $e,
            );
        }

        return new ListArgs([$result]);
    }

    private function normalize(string $value): string
    {
        return strtr($value, self::ESCAPE_REPLACEMENTS);
    }
}
