<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Manager;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemReader;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\StorageAttributes;
use PhpProject\SourceCode\Files\Constraint\FileConstraint;
use PhpProject\SourceCode\Files\Constraint\NullFileConstraint;
use PhpProject\SourceCode\Files\Path\AbsolutePath;
use PhpProject\SourceCode\Files\Path\RelativePath;
use PhpProject\SourceCode\Files\SourceFile;
use PhpProject\SourceCode\Files\SourceFiles;

final readonly class FileManager
{
    public function __construct(
        private AbsolutePath $baseAbsolutePath,
        private FilesystemOperator $fileSystem
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getFileContent(RelativePath $filePath): string
    {
        try {
            return $this->fileSystem->read((string) $filePath);
        } catch (FilesystemException $e) {
            throw new \InvalidArgumentException(sprintf('Could not read file. Path given: %s', $filePath), $e->getCode(), $e);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function save(RelativePath $filePath, string $content): void
    {
        try {
            $this->fileSystem->write((string) $filePath, $content);
        } catch (FilesystemException $e) {
            throw new \InvalidArgumentException(sprintf('Could not write file. Path given: %s', $filePath), $e->getCode(), $e);
        }
    }

    // TODO use a Path matcher instead of a path
    public function getSourceFilesFromPath(RelativePath $path, FileConstraint $fileConstraint = new NullFileConstraint()): SourceFiles
    {
        try {
            if ($this->fileSystem->fileExists((string) $path)) {
                return $this->getSourcesFromFilePath($path, $fileConstraint);
            }

            if ($this->fileSystem->directoryExists((string) $path)) {
                return $this->getSourcesFromDirectoryPath($path, $fileConstraint);
            }

            throw new \InvalidArgumentException(sprintf('The path given is neither a file nor a directory: %s', $path));
        } catch (FilesystemException $e) {
            throw new \InvalidArgumentException(sprintf('File system error. Path given: %s', $path), $e->getCode(), $e);
        }
    }

    public function getSourceFileFromPath(RelativePath $path): SourceFile
    {
        try {
            if (!$this->fileSystem->fileExists((string) $path)) {
                throw new \InvalidArgumentException(sprintf('The path given is not a file path: %s', $path));
            }
        } catch (FilesystemException $e) {
            throw new \InvalidArgumentException(sprintf('File system error. Path given: %s', $path), $e->getCode(), $e);
        }

        return SourceFile::fromPath($path);
    }

    public function getAbsolutePath(RelativePath $relativePath): AbsolutePath
    {
        return $this->baseAbsolutePath->append($relativePath);
    }

    public function getPath(string $path): RelativePath
    {
        if ($this->isAbsolutePath($path)) {
            return $this->getPathFromAbsolute($path);
        }

        return $this->getPathFromRelative($path);
    }

    // Helpers

    private function getSourcesFromFilePath(RelativePath $path, FileConstraint $fileConstraint): SourceFiles
    {
        $file = SourceFile::fromPath($path);

        if ($fileConstraint->isSatisfiedBy($file)) {
            return SourceFiles::fromSourceFile($file);
        }

        return SourceFiles::empty();
    }

    private function getPathFromAbsolute(string $path): RelativePath
    {
        return $this->baseAbsolutePath->getRelativePath(AbsolutePath::clean($path));
    }

    private function getPathFromRelative(string $path): RelativePath
    {
        return RelativePath::clean($path);
    }

    /**
     * @throws FilesystemException
     */
    private function getSourcesFromDirectoryPath(RelativePath $path, FileConstraint $fileConstraint): SourceFiles
    {
        /** @var array<FileAttributes> $files */
        $files          = $this->fileSystem->listContents((string) $path, FilesystemReader::LIST_DEEP)->toArray();
        $convertedFiles = array_map(
            static function (StorageAttributes $file): ?SourceFile {
                if (!$file->isFile()) {
                    return null;
                }

                return SourceFile::fromPath(RelativePath::raw($file->path()));
            },
            $files
        );

        $selectedFiles = array_filter(
            $convertedFiles,
            static fn (?SourceFile $file): bool => $file !== null && $fileConstraint->isSatisfiedBy($file),
        );

        return SourceFiles::fromSourceFile(...$selectedFiles);
    }

    private function isAbsolutePath(string $fileRelativePath): bool
    {
        return str_starts_with($fileRelativePath, (string) $this->baseAbsolutePath);
    }

    public static function build(AbsolutePath $projectPath): self
    {
        return new self(
            $projectPath,
            new Filesystem(
                new LocalFilesystemAdapter((string) $projectPath)
            )
        );
    }
}
