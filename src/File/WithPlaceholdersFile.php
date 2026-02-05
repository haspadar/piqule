<?php
declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Placeholders\Placeholders;

final readonly class WithPlaceholdersFile implements File
{
    public function __construct(
        private File $origin,
        private Placeholders $placeholders,
    ) {}

    public function name(): string
    {
        return $this->origin->name();
    }

    public function contents(): string
    {
        $contents = $this->origin->contents();

        foreach ($this->placeholders->all() as $placeholder) {
            $contents = strtr(
                $contents, [
                    $placeholder->expression() => $placeholder->replacement()
                ],
            );
        }

        return $contents;
    }
}
