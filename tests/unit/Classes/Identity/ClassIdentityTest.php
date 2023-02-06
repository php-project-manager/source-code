<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Identity;

use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('identity')] final class ClassIdentityTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_builds_from_a_reflection_class(): void
    {
        $reflectionClass = StandardReflectionClass::createFromName(self::class);
        $classIdentity   = ClassIdentity::fromReflectionClass($reflectionClass);

        self::assertEquals(self::class, $classIdentity->fqcn);
        self::assertEquals('ClassIdentityTest', $classIdentity->shortName);
        self::assertTrue($classIdentity->implementsInterface(Test::class));
    }
}
