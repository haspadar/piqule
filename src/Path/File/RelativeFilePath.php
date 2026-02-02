<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path\File;

use Haspadar\Piqule\Source\FileName;
use Override;

final readonly class RelativeFilePath implements FilePath
{
    public function __construct(private FileName $name) {}

    #[Override]
    public function value(): string
    {
        return $this->name->value();
    }
}
