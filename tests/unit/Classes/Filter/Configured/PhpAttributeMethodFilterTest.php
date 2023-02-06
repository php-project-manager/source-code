<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\Configured\PhpAttributeMethodFilter;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('filter')]
#[Group('class-filter')]
#[Group('configured-filter')] final class PhpAttributeMethodFilterTest extends TestCase
{
    protected function setUp(): void
    {
        Fixtures::load();
    }

    #[Test]
    public function it_matches_if_method_has_attribute(): void
    {
        $filterOne = new PhpAttributeMethodFilter(Test::class);

        self::assertTrue($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'it_matches_if_method_has_attribute'))));
        self::assertFalse($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myTestMethod'))));
        self::assertFalse($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myTagMethod'))));
        self::assertTrue($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myAttributeTestMethod'))));
        self::assertFalse($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myAttributeTagMethod'))));
        self::assertFalse($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myMainMethod'))));

        $filterTwo = new PhpAttributeMethodFilter(Group::class);

        self::assertFalse($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'it_matches_if_method_has_attribute'))));
        self::assertFalse($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myTestMethod'))));
        self::assertFalse($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myTagMethod'))));
        self::assertFalse($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myAttributeTestMethod'))));
        self::assertTrue($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myAttributeTagMethod'))));
        self::assertFalse($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(PhpDocTest::class, 'myMainMethod'))));
    }
}
