<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Constraint;

use PhpProject\SourceCode\Files\SourceFile;

interface FileConstraint
{
    public function isSatisfiedBy(SourceFile $file): bool;
}
