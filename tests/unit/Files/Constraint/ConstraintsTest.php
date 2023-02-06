<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Files\Constraint;

use PhpProject\SourceCode\Files\Constraint\CompoundFileConstraint;
use PhpProject\SourceCode\Files\Constraint\NullFileConstraint;
use PhpProject\SourceCode\Files\Constraint\PrefixFileConstraint;
use PhpProject\SourceCode\Files\Constraint\SuffixFileConstraint;
use PhpProject\SourceCode\Tests\Files\SourceFilesTest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-file')]
#[Group('filter')]
#[Group('file-filter')] final class ConstraintsTest extends TestCase
{
    #[Test]
    public function null_constraint_is_always_satisfied(): void
    {
        $nullConstraint = new NullFileConstraint();
        self::assertTrue($nullConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('file1')));
        self::assertTrue($nullConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('file2')));
    }

    #[Test]
    public function prefix_constraint_is_satisfied_if_short_name_starts_with_given_value(): void
    {
        $prefixConstraint = new PrefixFileConstraint('file');
        self::assertTrue($prefixConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('file1')));
        self::assertFalse($prefixConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('2file')));
    }

    #[Test]
    public function suffix_constraint_is_satisfied_if_short_name_ends_with_given_value(): void
    {
        $suffixConstraint = new SuffixFileConstraint('.ext');
        self::assertTrue($suffixConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('file.ext')));
        self::assertFalse($suffixConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('file.ext2')));
    }

    #[Group('compound-filter')]
    #[Test]
    public function compound_constraint_is_satisfied_if_all_its_subconstraints_are_satisfied(): void
    {
        $compoundConstraint = new CompoundFileConstraint(
            new PrefixFileConstraint('file'),
            new SuffixFileConstraint('.ext')
        );
        self::assertTrue($compoundConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('file.ext')));
        self::assertFalse($compoundConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('2file.ext')));
        self::assertFalse($compoundConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('file.ext2')));
        self::assertFalse($compoundConstraint->isSatisfiedBy(SourceFilesTest::sourceFile('2file.ext2')));
    }
}
