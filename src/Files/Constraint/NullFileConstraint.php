<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Constraint;

use PhpProject\SourceCode\Files\SourceFile;

final class NullFileConstraint implements FileConstraint
{
    public function isSatisfiedBy(SourceFile $file): bool
    {
        return true;
    }
}
