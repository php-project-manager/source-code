<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\MethodFilter;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;

final readonly class PhpAttributeMethodFilter implements MethodFilter
{
    /**
     * @param class-string $attributeClassName
     */
    public function __construct(
        private string $attributeClassName
    ) {
    }

    public function matchesMethod(MethodIdentity $methodIdentity): bool
    {
        return $methodIdentity->reflectionMethod->hasAttribute($this->attributeClassName);
    }
}
