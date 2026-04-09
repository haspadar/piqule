<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Actions\ParsedActions;
use Haspadar\Piqule\Formula\ExecutedFormula;
use Haspadar\Piqule\Formula\NormalizedFormula;
use Haspadar\Piqule\PiquleException;
use InvalidArgumentException;
use Override;

/**
 * Replaces DSL placeholders in a file's contents using configuration values.
 */
final readonly class ConfiguredFile implements File
{
    /**
     * Wraps a file with a set of named action factories for placeholder resolution.
     *
     * @param array<string, callable(string): Action> $actions
     */
    public function __construct(private File $origin, private array $actions) {}

    #[Override]
    public function name(): string
    {
        return $this->origin->name();
    }

    #[Override]
    public function contents(): string
    {
        return (string) preg_replace_callback(
            '/<<\s*(.*?)\s*>>/s',
            fn(array $match): string => $this->replaced($match[1]),
            $this->origin->contents(),
        );
    }

    #[Override]
    public function mode(): int
    {
        return $this->origin->mode();
    }

    /**
     * Returns the result of evaluating a single DSL expression against the config.
     *
     * @throws PiquleException
     */
    private function replaced(string $expression): string
    {
        try {
            return (new ExecutedFormula(
                new ParsedActions(
                    (new NormalizedFormula($expression))->result(),
                    $this->actions,
                ),
            ))->result();
        } catch (InvalidArgumentException | PiquleException $e) {
            throw new PiquleException(
                sprintf(
                    'File "%s", formula "%s": %s',
                    $this->name(),
                    trim($expression),
                    $e->getMessage(),
                ),
                0,
                $e,
            );
        }
    }
}
