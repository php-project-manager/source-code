<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes;

use PhpProject\SourceCode\Classes\Identity\MethodIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionMethod;
use PhpProject\SourceCode\Classes\SourceMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')] final class SourceMethodTest extends TestCase
{
    #[Test]
    public function it_can_be_built_from_reflection_method(): void
    {
        $reflectionMethod = StandardReflectionMethod::createFromName(SourceMethodEnclosingTestClass::class, 'methodEnclosed');

        $sourceMethod = SourceMethod::fromReflectionMethod($reflectionMethod);

        self::assertEquals('methodEnclosed', $sourceMethod->name());
        self::assertEquals(SourceMethodEnclosingTestClass::class, $sourceMethod->identity->class->fqcn);
        self::assertEquals('/**'.\PHP_EOL.' * My comments.'.\PHP_EOL.' */', $sourceMethod->docBlock);
        self::assertEquals('    public function methodEnclosed(): void'.\PHP_EOL.'    {'.\PHP_EOL.'        // My body'.\PHP_EOL.'    }', $sourceMethod->body);
    }

    #[Test]
    public function it_can_be_built_from_identity(): void
    {
        $reflectionMethod = StandardReflectionMethod::createFromName(SourceMethodEnclosingTestClass::class, 'methodEnclosed');
        $identity         = MethodIdentity::fromReflectionMethod($reflectionMethod);

        $sourceMethod = SourceMethod::fromIdentity($identity);

        self::assertEquals('methodEnclosed', $sourceMethod->name());
        self::assertEquals(SourceMethodEnclosingTestClass::class, $sourceMethod->identity->class->fqcn);
        self::assertEquals('/**'.\PHP_EOL.' * My comments.'.\PHP_EOL.' */', $sourceMethod->docBlock);
        self::assertEquals('    public function methodEnclosed(): void'.\PHP_EOL.'    {'.\PHP_EOL.'        // My body'.\PHP_EOL.'    }', $sourceMethod->body);
    }
}

final class SourceMethodEnclosingTestClass
{
    /**
     * My comments.
     */
    public function methodEnclosed(): void
    {
        // My body
    }
}
