<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\MethodFilter;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;

final class NullMethodFilter implements MethodFilter
{
    public function matchesMethod(MethodIdentity $methodIdentity): bool
    {
        return true;
    }
}
