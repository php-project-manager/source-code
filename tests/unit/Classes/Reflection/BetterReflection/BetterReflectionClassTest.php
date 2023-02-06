<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection\BetterReflection;

use PhpProject\SourceCode\Classes\Reflection\BetterReflection\BetterReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Tests\Classes\Reflection\AbstractReflectionClassTestCase;

final class BetterReflectionClassTest extends AbstractReflectionClassTestCase
{
    protected function buildFromClassName(string $className): ReflectionClass
    {
        return BetterReflectionClass::createFromName($className);
    }
}
