<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Envs\Envs;
use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use InvalidArgumentException;
use Override;

/**
 * Renders a GitHub Actions step that exports environment variables via $GITHUB_ENV.
 */
final readonly class EnvsAction implements Action
{
    /** Initializes with environment variables and YAML indentation prefix. */
    public function __construct(private Envs $envs, private string $indent) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        $vars = $this->envs->vars();

        if ($vars === []) {
            return new ListArgs(['']);
        }

        $indent = $this->unquotedIndent();
        $lines = [sprintf('%s    git fetch --tags --unshallow 2>/dev/null || git fetch --tags', $indent)];

        foreach ($vars as $name => $command) {
            $lines[] = sprintf(
                '%s    echo "%s=$(%s)" >> "$GITHUB_ENV"',
                $indent,
                $name,
                $command,
            );
        }

        return new ListArgs([
            sprintf(
                "%s- name: Set environment variables\n%s  run: |\n%s",
                $indent,
                $indent,
                implode("\n", $lines),
            ),
        ]);
    }

    /**
     * Strips outer quotes from the raw indent argument.
     *
     * @throws InvalidArgumentException
     */
    private function unquotedIndent(): string
    {
        $values = (new UnquotedArgs(
            new ListArgs([$this->indent]),
        ))->values();

        return (string) ($values[0] ?? '');
    }
}
