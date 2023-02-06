<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter;

use PhpProject\SourceCode\Classes\Identity\ClassIdentity;

interface ClassFilter
{
    public function matchesClass(ClassIdentity $classIdentity): bool;
}
