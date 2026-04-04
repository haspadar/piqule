<?php

declare(strict_types = 1);

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
    /** @var array<string, string> */
    private const array ESCAPE_REPLACEMENTS = [
        '\\\\' => '\\',
        '\\n' => "\n",
        '\\r' => "\r",
        '\\t' => "\t",
    ];

    public function __construct(private string $raw) {}

    /** @throws PiquleException */
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
        } catch (Throwable $e) {
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
