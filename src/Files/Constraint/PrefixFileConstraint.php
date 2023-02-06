<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Constraint;

use PhpProject\SourceCode\Files\SourceFile;

final readonly class PrefixFileConstraint implements FileConstraint
{
    public function __construct(private string $prefix)
    {
    }

    public function isSatisfiedBy(SourceFile $file): bool
    {
        return str_starts_with($file->shortName, $this->prefix);
    }
}
