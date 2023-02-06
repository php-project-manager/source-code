<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter;

use PhpProject\SourceCode\Classes\Filter\Configured\ImplementsClassFilter;
use PhpProject\SourceCode\Classes\Filter\Configured\NameStartsWithMethodFilter;
use PhpProject\SourceCode\Classes\Filter\Filter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionMethod;
use PhpProject\SourceCode\Tests\Classes\Filter\Configured\FirstClass;
use PhpProject\SourceCode\Tests\Classes\Filter\Configured\FirstInterface;
use PhpProject\SourceCode\Tests\Classes\Filter\Configured\Fixtures;
use PhpProject\SourceCode\Tests\Classes\Filter\Configured\SecondClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('filter')]
#[Group('class-filter')] final class FilterTest extends TestCase
{
    protected function setUp(): void
    {
        Fixtures::load();
    }

    #[Test]
    public function it_matches_all_classes_and_methods_if_not_filtering_anything(): void
    {
        $filter = Filter::null();

        $class  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(self::class));
        $method = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'it_matches_all_classes_and_methods_if_not_filtering_anything'));

        self::assertTrue($filter->matchesClass($class));
        self::assertTrue($filter->matchesMethod($method));
    }

    #[Test]
    public function it_matches_classes_the_given_class_filter_matches(): void
    {
        $classFilter = new ImplementsClassFilter(FirstInterface::class);
        $filter      = Filter::forClass($classFilter);

        $firstClass   = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(FirstClass::class));
        $secondClass  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(SecondClass::class));

        $firstMethod  = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(FirstClass::class, 'method'));
        $secondMethod = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(FirstClass::class, 'otherMethod'));

        self::assertTrue($classFilter->matchesClass($firstClass));
        self::assertTrue($filter->matchesClass($firstClass));

        self::assertFalse($classFilter->matchesClass($secondClass));
        self::assertFalse($filter->matchesClass($secondClass));

        self::assertTrue($filter->matchesMethod($firstMethod));
        self::assertTrue($filter->matchesMethod($secondMethod));
    }

    #[Test]
    public function it_matches_methods_the_given_method_filter_matches(): void
    {
        $methodFilter = new NameStartsWithMethodFilter('method');
        $filter       = Filter::forMethod($methodFilter);

        $firstClass   = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(FirstClass::class));
        $secondClass  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(SecondClass::class));

        $firstMethod  = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(FirstClass::class, 'method'));
        $secondMethod = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(FirstClass::class, 'otherMethod'));

        self::assertTrue($methodFilter->matchesMethod($firstMethod));
        self::assertTrue($filter->matchesMethod($firstMethod));

        self::assertFalse($methodFilter->matchesMethod($secondMethod));
        self::assertFalse($filter->matchesMethod($secondMethod));

        self::assertTrue($filter->matchesClass($firstClass));
        self::assertTrue($filter->matchesClass($secondClass));
    }
}
