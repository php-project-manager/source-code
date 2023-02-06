<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\ClassFilter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;

final class NullClassFilter implements ClassFilter
{
    public function matchesClass(ClassIdentity $classIdentity): bool
    {
        return true;
    }
}
