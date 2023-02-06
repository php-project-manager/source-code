<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Files\Path;

use PhpProject\SourceCode\Files\Path\AbsolutePath;
use PhpProject\SourceCode\Files\Path\RelativePath;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-file')]
#[Group('file-path')] final class RelativePathTest extends TestCase
{
    #[Test]
    public function it_should_fail_building_given_an_absolute_path(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        RelativePath::raw('/foo/bar');
    }

    #[Test]
    public function it_should_build_a_relative_path(): void
    {
        $path = RelativePath::raw('foo/bar');
        self::assertEquals('foo/bar', (string) $path);
    }

    #[Test]
    public function it_should_build_a_clean_relative_path(): void
    {
        $path = RelativePath::clean('foo/.////.//./bar/.///');
        self::assertEquals('foo/bar', (string) $path);
    }

    #[Test]
    public function it_should_recognize_the_sub_path(): void
    {
        $path     = AbsolutePath::clean('/foo/bar');
        $subPath  = RelativePath::clean('bar');
        $subPath2 = RelativePath::clean('baz');
        self::assertTrue($subPath->isSubPathOf($path));
        self::assertFalse($subPath2->isSubPathOf($path));
    }
}
