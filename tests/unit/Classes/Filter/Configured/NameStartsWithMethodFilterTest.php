<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\Configured\NameStartsWithMethodFilter;
use PhpProject\SourceCode\Classes\Filter\Filter;
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
#[Group('configured-filter')] final class NameStartsWithMethodFilterTest extends TestCase
{
    protected function setUp(): void
    {
        Fixtures::load();
    }

    #[Test]
    public function it_matches_if_method_starts_with_given_string(): void
    {
        $filterOne = new NameStartsWithMethodFilter('it_');

        self::assertTrue($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'it_matches_if_method_starts_with_given_string'))));
        self::assertFalse($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(Filter::class, 'matchesClass'))));
        self::assertFalse($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(Filter::class, 'matchesMethod'))));
        self::assertFalse($filterOne->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(\Stringable::class, '__toString'))));

        $filterTwo = new NameStartsWithMethodFilter('matches');

        self::assertFalse($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'it_matches_if_method_starts_with_given_string'))));
        self::assertTrue($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(Filter::class, 'matchesClass'))));
        self::assertTrue($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(Filter::class, 'matchesMethod'))));
        self::assertFalse($filterTwo->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(\Stringable::class, '__toString'))));

        $filterThree = new NameStartsWithMethodFilter('_');

        self::assertFalse($filterThree->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'it_matches_if_method_starts_with_given_string'))));
        self::assertFalse($filterThree->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(Filter::class, 'matchesClass'))));
        self::assertFalse($filterThree->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(Filter::class, 'matchesMethod'))));
        self::assertTrue($filterThree->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(\Stringable::class, '__toString'))));
    }
}
