<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter\Compound;

use PhpProject\SourceCode\Classes\Filter\ClassFilter;
use PhpProject\SourceCode\Classes\Filter\Compound\CompoundClassFilter;
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
#[Group('compound-filter')] final class CompoundClassFilterTest extends TestCase
{
    #[Test]
    public function it_matches_class_if_no_filter_given_when_using_and_operator(): void
    {
        $compoundClassFilter = CompoundClassFilter::And();

        $class  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(self::class));

        self::assertTrue($compoundClassFilter->matchesClass($class));
    }

    #[Test]
    public function it_matches_class_if_all_filters_match_the_class_when_using_and_operator(): void
    {
        $firstClassFilter  = $this->createMock(ClassFilter::class);
        $secondClassFilter = $this->createMock(ClassFilter::class);

        $compoundClassFilter = CompoundClassFilter::And($firstClassFilter, $secondClassFilter);

        $class  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(self::class));

        $firstClassFilter->method('matchesClass')->with($class)->willReturn(true);
        $secondClassFilter->method('matchesClass')->with($class)->willReturn(true);

        self::assertTrue($compoundClassFilter->matchesClass($class));
    }

    #[Test]
    public function it_does_not_match_class_if_at_least_one_filter_does_not_match_the_class_when_using_and_operator(): void
    {
        $firstClassFilter  = $this->createMock(ClassFilter::class);
        $secondClassFilter = $this->createMock(ClassFilter::class);

        $compoundClassFilter = CompoundClassFilter::And($firstClassFilter, $secondClassFilter);

        $class  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(self::class));

        $firstClassFilter->method('matchesClass')->with($class)->willReturn(true, false, false);
        $secondClassFilter->method('matchesClass')->with($class)->willReturn(false, true, false);

        self::assertFalse($compoundClassFilter->matchesClass($class));
        self::assertFalse($compoundClassFilter->matchesClass($class));
        self::assertFalse($compoundClassFilter->matchesClass($class));
    }

    #[Test]
    public function it_matches_class_if_no_filter_given_when_using_or_operator(): void
    {
        $compoundClassFilter = CompoundClassFilter::Or();

        $class  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(self::class));

        self::assertTrue($compoundClassFilter->matchesClass($class));
    }

    #[Test]
    public function it_matches_class_if_at_least_one_filter_matches_the_class_when_using_or_operator(): void
    {
        $firstClassFilter  = $this->createMock(ClassFilter::class);
        $secondClassFilter = $this->createMock(ClassFilter::class);

        $compoundClassFilter = CompoundClassFilter::Or($firstClassFilter, $secondClassFilter);

        $class  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(self::class));

        $firstClassFilter->method('matchesClass')->with($class)->willReturn(true, false, true);
        $secondClassFilter->method('matchesClass')->with($class)->willReturn(true, true, false);

        self::assertTrue($compoundClassFilter->matchesClass($class));
        self::assertTrue($compoundClassFilter->matchesClass($class));
        self::assertTrue($compoundClassFilter->matchesClass($class));
    }

    #[Test]
    public function it_does_not_match_class_if_none_of_the_filters_match_the_class_when_using_or_operator(): void
    {
        $firstClassFilter  = $this->createMock(ClassFilter::class);
        $secondClassFilter = $this->createMock(ClassFilter::class);

        $compoundClassFilter = CompoundClassFilter::And($firstClassFilter, $secondClassFilter);

        $class  = ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(self::class));

        $firstClassFilter->method('matchesClass')->with($class)->willReturn(false);
        $secondClassFilter->method('matchesClass')->with($class)->willReturn(false);

        self::assertFalse($compoundClassFilter->matchesClass($class));
    }
}
