<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Identity;

use PhpProject\SourceCode\Classes\Identity\MethodIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('identity')] final class MethodIdentityTest extends TestCase
{
    #[Test]
    public function it_builds_from_a_reflection_public_method(): void
    {
        $reflectionMethod = StandardReflectionMethod::createFromName(TestClass::class, 'publicTest');
        $methodIdentity   = MethodIdentity::fromReflectionMethod($reflectionMethod);

        self::assertEquals(TestClass::class, $methodIdentity->class->fqcn);
        self::assertEquals('publicTest', $methodIdentity->name);
        self::assertEquals(TestClass::class.'::publicTest', $methodIdentity->FQN());
        self::assertEquals($reflectionMethod, $methodIdentity->reflectionMethod);
        self::assertTrue($methodIdentity->isPublic());
        self::assertFalse($methodIdentity->isProtected());
        self::assertFalse($methodIdentity->isPrivate());
    }

    #[Test]
    public function it_builds_from_a_reflection_protected_method(): void
    {
        $reflectionMethod = StandardReflectionMethod::createFromName(TestClass::class, 'protectedTest');
        $methodIdentity   = MethodIdentity::fromReflectionMethod($reflectionMethod);

        self::assertEquals(TestClass::class, $methodIdentity->class->fqcn);
        self::assertEquals('protectedTest', $methodIdentity->name);
        self::assertEquals(TestClass::class.'::protectedTest', $methodIdentity->FQN());
        self::assertEquals($reflectionMethod, $methodIdentity->reflectionMethod);
        self::assertFalse($methodIdentity->isPublic());
        self::assertTrue($methodIdentity->isProtected());
        self::assertFalse($methodIdentity->isPrivate());
    }

    #[Test]
    public function it_builds_from_a_reflection_private_method(): void
    {
        $reflectionMethod = StandardReflectionMethod::createFromName(TestClass::class, 'privateTest');
        $methodIdentity   = MethodIdentity::fromReflectionMethod($reflectionMethod);

        self::assertEquals(TestClass::class, $methodIdentity->class->fqcn);
        self::assertEquals('privateTest', $methodIdentity->name);
        self::assertEquals(TestClass::class.'::privateTest', $methodIdentity->FQN());
        self::assertEquals($reflectionMethod, $methodIdentity->reflectionMethod);
        self::assertFalse($methodIdentity->isPublic());
        self::assertFalse($methodIdentity->isProtected());
        self::assertTrue($methodIdentity->isPrivate());
    }
}

class TestClass
{
    public function publicTest(): void
    {
        $this->protectedTest();
    }

    protected function protectedTest(): void
    {
        $this->privateTest();
    }

    private function privateTest(): void
    {
    }
}
