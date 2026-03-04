<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Files;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Files\FilteredFiles;
use Haspadar\Piqule\Files\TextFiles;
use Haspadar\Piqule\Tests\Constraint\Files\HasFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FilteredFilesTest extends TestCase
{
    #[Test]
    public function returnsOnlyMatchingFilesWhenPredicateFiltersOut(): void
    {
        self::assertThat(
            new FilteredFiles(
                new TextFiles([
                    'pre-push' => 'exit 1',
                    'pre-push-piqule' => 'exit 2',
                    'commit-msg' => 'exit 3',
                ]),
                fn(File $file): bool => !str_ends_with($file->name(), 'pre-push'),
            ),
            new HasFiles([
                'pre-push-piqule' => 'exit 2',
                'commit-msg' => 'exit 3',
            ]),
            'FilteredFiles must exclude files that do not satisfy the predicate',
        );
    }

    #[Test]
    public function returnsEmptyListWhenNoFilesMatchPredicate(): void
    {
        self::assertThat(
            new FilteredFiles(
                new TextFiles([
                    'pre-push' => 'exit 0',
                ]),
                fn(File $file): bool => str_starts_with($file->name(), 'commit'),
            ),
            new HasFiles([]),
            'FilteredFiles must return empty list when no files satisfy the predicate',
        );
    }

    #[Test]
    public function returnsAllFilesWhenAllMatchPredicate(): void
    {
        self::assertThat(
            new FilteredFiles(
                new TextFiles([
                    'pre-push' => 'run push checks',
                    'pre-commit' => 'run commit checks',
                ]),
                fn(File $file): bool => str_starts_with($file->name(), 'pre-'),
            ),
            new HasFiles([
                'pre-push' => 'run push checks',
                'pre-commit' => 'run commit checks',
            ]),
            'FilteredFiles must return all files when every file satisfies the predicate',
        );
    }
}
