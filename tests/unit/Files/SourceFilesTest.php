<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Files;

use PhpProject\SourceCode\Files\Path\RelativePath;
use PhpProject\SourceCode\Files\SourceFile;
use PhpProject\SourceCode\Files\SourceFiles;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-file')] final class SourceFilesTest extends TestCase
{
    #[Test]
    public function it_builds_an_empty_list(): void
    {
        $files = SourceFiles::empty();
        self::assertCount(0, $files);
        self::assertCount(0, $files->asArray());
    }

    #[Test]
    public function it_builds_from_a_list_of_paths(): void
    {
        $files = SourceFiles::fromPaths(
            SourceFileTest::path('file1'),
            SourceFileTest::path('file2'),
            SourceFileTest::path('file3')
        );
        self::assertCount(3, $files);

        $array = $files->asArray();
        self::assertCount(3, $array);
        self::assertEquals('file1', $array[0]->shortName);
        self::assertEquals('file2', $array[1]->shortName);
        self::assertEquals('file3', $array[2]->shortName);
    }

    #[Test]
    public function it_builds_from_a_list_of_files(): void
    {
        $files = SourceFiles::fromSourceFile(
            self::sourceFile('file1'),
            self::sourceFile('file2'),
            self::sourceFile('file3')
        );
        self::assertCount(3, $files);

        $array = $files->asArray();
        self::assertCount(3, $array);
        self::assertEquals('file1', $array[0]->shortName);
        self::assertEquals('file2', $array[1]->shortName);
        self::assertEquals('file3', $array[2]->shortName);
    }

    #[Test]
    public function it_builds_from_other_source_files(): void
    {
        $files = SourceFiles::fromSourceFiles(
            self::sourceFiles('file1', 'file2'),
            self::sourceFiles('file3', 'file4'),
        );
        self::assertCount(4, $files);

        $array = $files->asArray();
        self::assertCount(4, $array);
        self::assertEquals('file1', $array[0]->shortName);
        self::assertEquals('file2', $array[1]->shortName);
        self::assertEquals('file3', $array[2]->shortName);
        self::assertEquals('file4', $array[3]->shortName);
    }

    #[Test]
    public function it_is_traversable(): void
    {
        $files = self::sourceFiles('file1', 'file2', 'file3');

        $fileNames = [];
        foreach ($files as $file) {
            $fileNames[] = $file->shortName;
        }

        self::assertEquals(['file1', 'file2', 'file3'], $fileNames);
    }

    #[Test]
    public function it_can_add_other_source_files(): void
    {
        $files = self::sourceFiles('file1', 'file2');
        $files = $files->add(self::sourceFiles('file3', 'file4'));
        self::assertCount(4, $files);

        $array = $files->asArray();
        self::assertCount(4, $array);
        self::assertEquals('file1', $array[0]->shortName);
        self::assertEquals('file2', $array[1]->shortName);
        self::assertEquals('file3', $array[2]->shortName);
        self::assertEquals('file4', $array[3]->shortName);
    }

    #[Test]
    public function it_can_remove_source_files(): void
    {
        $files = self::sourceFiles('file1', 'file2', 'file3', 'file4');
        $files = $files->remove(self::sourceFiles('file2', 'file4'));
        self::assertCount(2, $files);

        $array = $files->asArray();
        self::assertCount(2, $array);
        self::assertEquals('file1', $array[0]->shortName);
        self::assertEquals('file3', $array[1]->shortName);
    }

    // Helpers

    public static function sourceFiles(string ...$files): SourceFiles
    {
        return SourceFiles::fromSourceFile(
            ...array_map(
                static fn (string $file): SourceFile => self::sourceFile($file),
                $files
            )
        );
    }

    public static function sourceFile(string $path): SourceFile
    {
        return SourceFile::fromPath(
            RelativePath::raw($path)
        );
    }
}
