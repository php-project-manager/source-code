<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\Configured\ImplementsClassFilter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('filter')]
#[Group('class-filter')]
#[Group('configured-filter')] final class ImplementsClassFilterTest extends TestCase
{
    protected function setUp(): void
    {
        Fixtures::load();
    }

    #[Test]
    public function it_matches_if_class_implements_given_interface(): void
    {
        $filterOne   = new ImplementsClassFilter(FirstInterface::class);

        self::assertTrue($filterOne->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(FirstClass::class))));
        self::assertFalse($filterOne->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(SecondClass::class))));

        $filterTwo   = new ImplementsClassFilter(SecondInterface::class);

        self::assertFalse($filterTwo->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(FirstClass::class))));
        self::assertTrue($filterTwo->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(SecondClass::class))));

        $filterThree = new ImplementsClassFilter(ThirdInterface::class);

        self::assertTrue($filterThree->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(FirstClass::class))));
        self::assertFalse($filterThree->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(SecondClass::class))));
    }
}
