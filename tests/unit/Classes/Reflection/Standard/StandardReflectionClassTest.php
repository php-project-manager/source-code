<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection\Standard;

use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionClass;
use PhpProject\SourceCode\Tests\Classes\Reflection\AbstractReflectionClassTestCase;

final class StandardReflectionClassTest extends AbstractReflectionClassTestCase
{
    protected function buildFromClassName(string $className): ReflectionClass
    {
        return StandardReflectionClass::createFromName($className);
    }
}
