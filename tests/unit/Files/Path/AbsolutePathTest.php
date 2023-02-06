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
#[Group('file-path')] final class AbsolutePathTest extends TestCase
{
    #[Test]
    public function it_should_fail_building_given_a_relative_path(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        AbsolutePath::raw('foo/bar');
    }

    #[Test]
    public function it_should_build_an_absolute_path(): void
    {
        $path = AbsolutePath::raw('/foo/bar');

        self::assertEquals('/foo/bar', (string) $path);
    }

    #[Test]
    public function it_should_build_a_clean_absolute_path(): void
    {
        $path = AbsolutePath::clean('/foo/.////.//./bar/.///');

        self::assertEquals('/foo/bar', (string) $path);
    }

    #[Test]
    public function it_should_recognize_same_path_as_sub_path(): void
    {
        $path     = AbsolutePath::raw('/foo/bar');
        $subPath  = AbsolutePath::raw('/foo/bar');
        $subPath2 = AbsolutePath::raw('/baz');

        self::assertTrue($subPath->isSubPathOf($path));
        self::assertFalse($subPath2->isSubPathOf($path));
    }

    #[Test]
    public function it_can_append_a_relative_path(): void
    {
        $path    = AbsolutePath::raw('/foo/bar');
        $subPath = RelativePath::raw('baz');

        self::assertEquals('/foo/bar/baz', (string) $path->append($subPath));
    }

    #[Test]
    public function it_can_extract_a_relative_path(): void
    {
        $path    = AbsolutePath::raw('/foo/bar');
        $subPath = AbsolutePath::raw('/foo/bar/baz');

        self::assertEquals('baz', (string) $path->getRelativePath($subPath));
    }
}
