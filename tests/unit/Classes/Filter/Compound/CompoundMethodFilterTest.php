<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter\Compound;

use PhpProject\SourceCode\Classes\Filter\Compound\CompoundMethodFilter;
use PhpProject\SourceCode\Classes\Filter\MethodFilter;
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
#[Group('compound-filter')] final class CompoundMethodFilterTest extends TestCase
{
    protected function setUp(): void
    {
    }

    #[Test]
    public function it_matches_method_if_no_filter_given_when_using_and_operator(): void
    {
        $compoundMethodFilter = CompoundMethodFilter::And();

        $method  = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'setUp'));

        self::assertTrue($compoundMethodFilter->matchesMethod($method));
    }

    #[Test]
    public function it_matches_method_if_all_filters_match_the_method_when_using_and_operator(): void
    {
        $firstClassFilter  = $this->createMock(MethodFilter::class);
        $secondClassFilter = $this->createMock(MethodFilter::class);

        $compoundMethodFilter = CompoundMethodFilter::And($firstClassFilter, $secondClassFilter);

        $method  = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'setUp'));

        $firstClassFilter->method('matchesMethod')->with($method)->willReturn(true);
        $secondClassFilter->method('matchesMethod')->with($method)->willReturn(true);

        self::assertTrue($compoundMethodFilter->matchesMethod($method));
    }

    #[Test]
    public function it_does_not_match_method_if_at_least_one_filter_does_not_match_the_method_when_using_and_operator(): void
    {
        $firstClassFilter  = $this->createMock(MethodFilter::class);
        $secondClassFilter = $this->createMock(MethodFilter::class);

        $compoundMethodFilter = CompoundMethodFilter::And($firstClassFilter, $secondClassFilter);

        $method  = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'setUp'));

        $firstClassFilter->method('matchesMethod')->with($method)->willReturn(true, false, false);
        $secondClassFilter->method('matchesMethod')->with($method)->willReturn(false, true, false);

        self::assertFalse($compoundMethodFilter->matchesMethod($method));
        self::assertFalse($compoundMethodFilter->matchesMethod($method));
        self::assertFalse($compoundMethodFilter->matchesMethod($method));
    }

    #[Test]
    public function it_matches_method_if_no_filter_given_when_using_or_operator(): void
    {
        $compoundMethodFilter = CompoundMethodFilter::Or();

        $method  = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'setUp'));

        self::assertTrue($compoundMethodFilter->matchesMethod($method));
    }

    #[Test]
    public function it_matches_method_if_at_least_one_filter_matches_the_method_when_using_or_operator(): void
    {
        $firstClassFilter  = $this->createMock(MethodFilter::class);
        $secondClassFilter = $this->createMock(MethodFilter::class);

        $compoundMethodFilter = CompoundMethodFilter::Or($firstClassFilter, $secondClassFilter);

        $method  = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'setUp'));

        $firstClassFilter->method('matchesMethod')->with($method)->willReturn(true, false, true);
        $secondClassFilter->method('matchesMethod')->with($method)->willReturn(true, true, false);

        self::assertTrue($compoundMethodFilter->matchesMethod($method));
        self::assertTrue($compoundMethodFilter->matchesMethod($method));
        self::assertTrue($compoundMethodFilter->matchesMethod($method));
    }

    #[Test]
    public function it_does_not_match_method_if_none_of_the_filters_match_the_method_when_using_or_operator(): void
    {
        $firstClassFilter  = $this->createMock(MethodFilter::class);
        $secondClassFilter = $this->createMock(MethodFilter::class);

        $compoundMethodFilter = CompoundMethodFilter::And($firstClassFilter, $secondClassFilter);

        $method  = MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'setUp'));

        $firstClassFilter->method('matchesMethod')->with($method)->willReturn(false);
        $secondClassFilter->method('matchesMethod')->with($method)->willReturn(false);

        self::assertFalse($compoundMethodFilter->matchesMethod($method));
    }
}
