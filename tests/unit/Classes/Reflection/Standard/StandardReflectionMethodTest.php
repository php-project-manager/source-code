<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection\Standard;

use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionMethod;
use PhpProject\SourceCode\Tests\Classes\Reflection\AbstractReflectionMethodTestCase;

final class StandardReflectionMethodTest extends AbstractReflectionMethodTestCase
{
    protected function buildFromClassNameAndMethodName(string $className, string $methodName): ReflectionMethod
    {
        return StandardReflectionMethod::createFromName($className, $methodName);
    }
}
