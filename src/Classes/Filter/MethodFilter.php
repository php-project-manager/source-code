<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter;

use PhpProject\SourceCode\Classes\Identity\MethodIdentity;

interface MethodFilter
{
    public function matchesMethod(MethodIdentity $methodIdentity): bool;
}
