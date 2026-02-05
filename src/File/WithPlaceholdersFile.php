<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Placeholders\Placeholders;
use Override;

final readonly class WithPlaceholdersFile implements File
{
    public function __construct(
        private File $origin,
        private Placeholders $placeholders,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->origin->name();
    }

    #[Override]
    public function contents(): string
    {
        $contents = $this->origin->contents();

        foreach ($this->placeholders->all() as $placeholder) {
            $contents = strtr(
                $contents,
                [
                    $placeholder->expression() => $placeholder->replacement(),
                ],
            );
        }

        return $contents;
    }
}
