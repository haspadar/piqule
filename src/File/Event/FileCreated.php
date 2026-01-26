<?php
declare(strict_types=1);

namespace Haspadar\Piqule\File\Event;

use Haspadar\Piqule\File\Target\FileTarget;
use Override;

final readonly class FileCreated implements FileEvent
{
    public function __construct(private string $name)
    {
    }

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param FileTarget $target
     * @return void
     */
    #[Override]
    public function passTo(FileTarget $target): void
    {
        $target->created($this);
    }
}
