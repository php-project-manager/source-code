<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Constraint;

use PhpProject\SourceCode\Files\SourceFile;

final readonly class SuffixFileConstraint implements FileConstraint
{
    public function __construct(private string $suffix)
    {
    }

    public function isSatisfiedBy(SourceFile $file): bool
    {
        return str_ends_with((string) $file->path, $this->suffix);
    }
}
