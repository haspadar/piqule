<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Actions\ParsedActions;
use Haspadar\Piqule\Formula\ExecutedFormula;
use Haspadar\Piqule\Formula\NormalizedFormula;
use Haspadar\Piqule\PiquleException;
use Override;
use Throwable;

final readonly class ConfiguredFile implements File
{
    /**
     * @param array<string, callable(string): Action> $actions
     */
    public function __construct(
        private File $origin,
        private array $actions,
    ) {}

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

    private function replaced(string $expression): string
    {
        try {
            return (new ExecutedFormula(
                new ParsedActions(
                    (new NormalizedFormula($expression))->result(),
                    $this->actions,
                ),
            ))->result();
        } catch (Throwable $e) {
            throw new PiquleException(
                sprintf(
                    'File "%s", formula "%s": %s',
                    $this->name(),
                    trim($expression),
                    $e->getMessage(),
                ),
                previous: $e,
            );
        }
    }
}
