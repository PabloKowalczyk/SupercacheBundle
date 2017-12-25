<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Filesystem;

use PabloK\SupercacheBundle\Exceptions\EmptyPathException;
use PabloK\SupercacheBundle\Exceptions\FilesystemException;
use PabloK\SupercacheBundle\Exceptions\PathNotFoundException;
use PabloK\SupercacheBundle\Exceptions\SecurityViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use voku\helper\HtmlMin;

/**
 * Handles all filesystem operations.
 * This class always uses / (slash) as directory separator, however it accepts \.
 */
class Finder
{
    const CACHE_FILE_REGEX = '/index\.(html|js|bin)$/';

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var LoggerInterface|null
     */
    private $logger;
    /**
     * @var HtmlMin
     */
    private $htmlMin;

    /**
     * @param string $cacheDir Patch to caching directory
     *
     * @throws FilesystemException Unknown or invalid cache directory specified. It may be a permission problem.
     * @throws EmptyPathException  When passed empty $cacheDir
     */
    public function __construct(
        string $cacheDir,
        Filesystem $filesystem,
        HtmlMin $htmlMin,
        LoggerInterface $logger = null
    ) {
        if (empty($cacheDir)) {
            throw new EmptyPathException('Cache directory cannot be empty.');
        }

        if (!$filesystem->exists($cacheDir)) {
            $filesystem->mkdir($cacheDir);
        }

        $realCacheDir = $this->unixRealpath($cacheDir);

        if (!$realCacheDir) {
            throw new FilesystemException("Supercache data directory {$cacheDir} is invalid or inaccessible.");
        }

        $this->cacheDir = $realCacheDir;
        $this->logger = $logger;
        $this->htmlMin = $htmlMin;
    }

    /**
     * Provides real filesystem path to cache directory (with all variables and symlinks resolved).
     *
     * @return string
     */
    public function getRealCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * Provides raw list of cache files.
     *
     * @return \Iterator|\SplFileInfo[]
     *
     * @throw UnexpectedValueException Exception is thrown while cache directory cannot be opened or traversed.
     */
    public function getFilesList()
    {
        $dir = new \RecursiveDirectoryIterator($this->cacheDir, \FilesystemIterator::CURRENT_AS_FILEINFO);
        $ite = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::CHILD_FIRST);
        $files = new \RegexIterator($ite, self::CACHE_FILE_REGEX);

        return $files;
    }

    /**
     * Get cache file content.
     *
     * @param string $path
     *
     * @return string|false will return file contents or false if cannot be retrieved
     *
     * @throws PathNotFoundException
     * @throws SecurityViolationException Thrown while path is not located under cache directory
     * @throws \InvalidArgumentException  Given path doesn't look like file created by this bundle
     */
    public function readFile($path)
    {
        try {
            $path = $this->getAbsolutePathFromRelative($path);
        } catch (PathNotFoundException $e) {
            return false;
        }

        if (!\preg_match(static::CACHE_FILE_REGEX, $path)) {
            throw new \InvalidArgumentException("Finder can only read cache files - $path is not one of them");
        }

        return @\file_get_contents($path);
    }

    /**
     * Deletes cache file.
     *
     * @throws PathNotFoundException
     * @throws SecurityViolationException Thrown while path is not located under cache directory
     * @throws \InvalidArgumentException  Given path doesn't look like file created by this bundle
     */
    public function deleteFile(string $path): bool
    {
        $path = $this->getAbsolutePathFromRelative($path);
        if (!$path) {
            return false;
        }

        if (!\preg_match(static::CACHE_FILE_REGEX, $path)) {
            throw new \InvalidArgumentException("Finder can only delete cache files - $path is not one of them");
        }

        return @\unlink($path);
    }

    /**
     * Deletes EMPTY directory.
     *
     * @throws PathNotFoundException
     * @throws SecurityViolationException Thrown while path is not located under cache directory
     */
    public function deleteDirectory(string $path): bool
    {
        $path = $this->getAbsolutePathFromRelative($path);

        return $path && @\rmdir($path);
    }

    /**
     * Deletes directory with it's content.
     *
     * @param bool $safeDelete by default this option is enabled and prevents deleting files and folders not created by
     *                         that bundle
     *
     * @throws FilesystemException
     * @throws PathNotFoundException
     * @throws SecurityViolationException Thrown while path is not located under cache directory
     * @throws \RuntimeException          Exception is used while unknown file is found and $safeDelete is enabled
     */
    public function deleteDirectoryRecursive(string $path, bool $safeDelete = true): bool
    {
        $path = $this->getAbsolutePathFromRelative($path);
        if (empty($path)) {
            return false;
        }

        $dir = new \RecursiveDirectoryIterator(
            $path,
            \RecursiveDirectoryIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::UNIX_PATHS
        );
        $iterator = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::CHILD_FIRST);

        /** @var \SplFileInfo $fileInfo */
        foreach ($iterator as $fileInfo) {
            $realPath = $this->unixRealpath($fileInfo->getRealPath());

            if ($fileInfo->isDir()) {
                if (@!\rmdir($realPath)) {
                    throw new FilesystemException('Failed to delete directory ' . $realPath);
                }
            } else {
                if (0 === \strpos($realPath, $this->cacheDir . '/.')) { //Skip dot-files in root directory
                    continue;
                }

                if ($safeDelete && !\preg_match(self::CACHE_FILE_REGEX, $realPath)) {
                    throw new \RuntimeException('Finder found unknown file ' . $realPath . ' - this was not created by SupercacheBundle. Inspect this issue manually.');
                }

                if (@!\unlink($realPath)) {
                    throw new FilesystemException('Failed to delete file ' . $realPath);
                }
            }
        }

        if ($path !== $this->cacheDir) {
            return \rmdir($path);
        }

        return true;
    }

    /**
     * Saves content to given cache file.
     *
     * @throws FilesystemException
     * @throws SecurityViolationException Should never be thrown from this method. If thrown it means some security
     *                                    mechanisms failed to sanitize $path and file gets created outside cache directory. This method is smart
     *                                    enough to rollback that change by deleting file (so no harm is expected). If you seen that exception while
     *                                    calling this method fill bug report asap.
     * @throws \InvalidArgumentException  given path doesn't look like file created by this bundle
     */
    public function writeFile(
        string $path,
        string $content,
        bool $minifyHtml
    ): bool {
        $fullPath = \preg_replace('/(\/|\\\)+/', '/', $this->cacheDir . '/' . $path); //Convert to absolute path with unix separators & remove duplicated slashed

        if (\preg_match('/\/\.\.|\.\.\//', $fullPath)) { //Don't look at me that way, please...
            throw new SecurityViolationException("Requested path $path is invalid");
        }

        if (!\preg_match(self::CACHE_FILE_REGEX, $fullPath)) {
            throw new \InvalidArgumentException("Finder can only create cache files - $path is not one of them");
        }

        $info = \pathinfo($fullPath);
        if (!isset($info['dirname']) || (!\is_dir($info['dirname']) && !\mkdir($info['dirname'], 0777, true))) {
            throw new FilesystemException('Failed to create directory for ' . $path);
        }

        if ($minifyHtml) {
            $content = $this->htmlMin
                ->minify($content);
        }

        if (false === \file_put_contents($fullPath, $content)) {
            $this->logError(
                "Unable to write cache file $fullPath (requested write to $path)",
                ['content' => $content]
            );

            return false;
        }

        //Last verification to be 100% sure we did the right thing...
        try {
            $this->getAbsolutePathFromRelative($path);

            return true;
        } catch (\Exception $e) {
            @\unlink($fullPath); //Remove bogus file

            $this->logError(
                "Cache file verification failed after write - file not found after writing to $fullPath (requested $path)"
            );

            if ($e instanceof SecurityViolationException) {
                $this->logError(
                    "Cache file verification failed after write - file not found after writing to $fullPath (requested $path)"
                );
            }

            return false;
        }
    }

    /**
     * Checks if given cache file/folder is readable.
     *
     * @return bool
     *
     * @throws SecurityViolationException tried to access file outside cache directory
     */
    public function isReadable($path)
    {
        try {
            $this->getAbsolutePathFromRelative($path);

            return true;
        } catch (PathNotFoundException $e) {
            return false;
        }
    }

    /**
     * Method works like realpath(), but it always return paths with slashes (while builtin one uses backslashes on Windos).
     *
     * @return string unlike realpath() this method never returns false
     */
    private function unixRealpath($path)
    {
        return (DIRECTORY_SEPARATOR === '/') ? (string) \realpath($path) : \str_replace('\\', '/', \realpath($path));
    }

    /**
     * Converts relative path into physical path in filesystem.
     *
     * @param string $path
     *
     * @return string
     *
     * @throws PathNotFoundException
     * @throws SecurityViolationException Thrown while path is not located under cache directory
     */
    private function getAbsolutePathFromRelative($path)
    {
        $absolute = $this->unixRealpath($this->cacheDir . '/' . $path);

        if (empty($absolute)) {
            throw new PathNotFoundException($path, 'entry cannot be located inside supercache directory');
        }

        if (0 !== \strpos($absolute, $this->cacheDir)) {
            throw new SecurityViolationException("Requested path $path is located above supercache directory");
        }

        return $absolute;
    }

    private function logError(string $message, array $context = [])
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger
            ->error($message, $context);
    }
}
