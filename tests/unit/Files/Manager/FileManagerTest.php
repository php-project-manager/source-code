<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Files\Manager;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use PhpProject\SourceCode\Files\Constraint\PrefixFileConstraint;
use PhpProject\SourceCode\Files\Constraint\SuffixFileConstraint;
use PhpProject\SourceCode\Files\Manager\FileManager;
use PhpProject\SourceCode\Files\Path\AbsolutePath;
use PhpProject\SourceCode\Files\Path\RelativePath;
use PhpProject\SourceCode\Tests\Files\SourceFileTest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-file')]
#[Group('file-manager')] final class FileManagerTest extends TestCase
{
    private FilesystemOperator $fileSystem;
    private FileManager $fileManager;

    protected function setUp(): void
    {
        $this->fileSystem = new Filesystem(new InMemoryFilesystemAdapter());

        $this->fileManager = new FileManager(
            AbsolutePath::raw('/project'),
            $this->fileSystem
        );
    }

    #[Test]
    public function it_saves_and_gets_source_file_content(): void
    {
        $path = self::path('test.txt');
        $this->fileManager->save($path, 'content');

        self::assertEquals('content', $this->fileManager->getFileContent($path));
    }

    #[Test]
    public function it_cannot_save_file_to_invalid_path(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->fileManager->save(self::path('../../invalid.txt'), 'content');
    }

    #[Test]
    public function it_cannot_get_non_existing_source_file_content(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->fileManager->getFileContent(SourceFileTest::path('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    #[Test]
    public function it_gets_a_single_source_file_from_path(): void
    {
        $this->fileSystem->write('test.txt', 'content');

        $sourceFiles = $this->fileManager->getSourceFilesFromPath(self::path('test.txt'));
        self::assertCount(1, $sourceFiles);

        $array = $sourceFiles->asArray();
        self::assertEquals('test.txt', (string) $array[0]->path);
    }

    /**
     * @throws FilesystemException
     */
    #[Test]
    public function it_gets_nothing_from_path_if_file_does_not_matches_constraints(): void
    {
        $this->fileSystem->write('test.txt', 'content');

        $sourceFiles = $this->fileManager->getSourceFilesFromPath(
            self::path('test.txt'),
            new PrefixFileConstraint('not')
        );
        self::assertCount(0, $sourceFiles);
    }

    /**
     * @throws FilesystemException
     */
    #[Test]
    public function it_gets_all_source_files_from_path(): void
    {
        $this->fileSystem->write('test.txt', 'content');
        $this->fileSystem->write('test2.txt', 'content');
        $this->fileSystem->write('test3.txt', 'content');

        $sourceFiles = $this->fileManager->getSourceFilesFromPath(self::path(''));
        self::assertCount(3, $sourceFiles);

        $array = $sourceFiles->asArray();
        self::assertEquals('test.txt', (string) $array[0]->path);
        self::assertEquals('test2.txt', (string) $array[1]->path);
        self::assertEquals('test3.txt', (string) $array[2]->path);
    }

    /**
     * @throws FilesystemException
     */
    #[Test]
    public function it_gets_only_source_files_matching_constraints_from_path(): void
    {
        $this->fileSystem->write('test.txt', 'content');
        $this->fileSystem->write('test2.pdf', 'content');
        $this->fileSystem->write('test3.txt', 'content');

        $sourceFiles = $this->fileManager->getSourceFilesFromPath(
            self::path(''),
            new SuffixFileConstraint('.txt')
        );
        self::assertCount(2, $sourceFiles);

        $array = $sourceFiles->asArray();
        self::assertEquals('test.txt', (string) $array[0]->path);
        self::assertEquals('test3.txt', (string) $array[1]->path);
    }

    #[Test]
    public function it_fails_getting_non_existing_files_from_path(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->fileManager->getSourceFilesFromPath(self::path('unknown'));
    }

    #[Test]
    public function it_fails_getting_files_from_invalid_path(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->fileManager->getSourceFilesFromPath(self::path('../../invalid/'));
    }

    #[Test]
    public function it_gets_source_file_from_path(): void
    {
        $path = self::path('test.txt');
        $this->fileManager->save($path, 'content');

        $sourceFile = $this->fileManager->getSourceFileFromPath($path);

        self::assertEquals('test.txt', (string) $sourceFile->path);
    }

    #[Test]
    public function it_cannot_get_source_file_from_path_if_it_does_not_exist(): void
    {
        $path = self::path('test.txt');

        $this->expectException(\InvalidArgumentException::class);
        $this->fileManager->getSourceFileFromPath($path);
    }

    #[Test]
    public function it_fails_getting_file_from_invalid_path(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->fileManager->getSourceFileFromPath(self::path('../../invalid.txt'));
    }

    #[Test]
    public function it_gets_absolute_path_from_relative_path(): void
    {
        $path = $this->fileManager->getAbsolutePath(RelativePath::raw('test.txt'));
        self::assertEquals('/project/test.txt', (string) $path);
    }

    #[Test]
    public function it_gets_path_from_relative_string(): void
    {
        $path = $this->fileManager->getPath('test.txt');
        self::assertEquals('test.txt', (string) $path);
    }

    #[Test]
    public function it_gets_path_from_absolute_string(): void
    {
        $path = $this->fileManager->getPath('/project/test.txt');
        self::assertEquals('test.txt', (string) $path);
    }

    #[Test]
    public function it_fails_getting_path_from_absolute_string_if_base_path_is_not_in_absolute_path(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->fileManager->getPath('/unknown/test.txt');
    }

    private static function path(string $path): RelativePath
    {
        return RelativePath::raw($path);
    }
}
