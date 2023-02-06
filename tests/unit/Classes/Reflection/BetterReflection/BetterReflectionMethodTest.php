<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection\BetterReflection;

use PhpProject\SourceCode\Classes\Reflection\BetterReflection\BetterReflectionMethod;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use PhpProject\SourceCode\Tests\Classes\Reflection\AbstractReflectionMethodTestCase;

final class BetterReflectionMethodTest extends AbstractReflectionMethodTestCase
{
    protected function buildFromClassNameAndMethodName(string $className, string $methodName): ReflectionMethod
    {
        return BetterReflectionMethod::createFromName($className, $methodName);
    }
}
