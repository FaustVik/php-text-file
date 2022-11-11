<?php

declare(strict_types=1);

namespace FaustVik\Files\Directory;

use FaustVik\Files\Exceptions\DirectoryException;
use FaustVik\Files\Exceptions\IsNotResource;
use FaustVik\Files\Helpers\FileOperation;

use function closedir;
use function explode;
use function is_dir;
use function is_resource;
use function mkdir;
use function opendir;
use function readdir;
use function rmdir;

/**
 * Class DirectoryOperation
 * @package FaustVik\Files\Directory
 */
final class DirectoryOperation
{
    /**
     * Creating nested directories
     *
     * @param string $path
     */
    public static function creatingNestedDirectories(string $path): void
    {
        $tags  = explode('/', $path);
        $mkDir = "";

        foreach ($tags as $folder) {
            $mkDir .= $folder . "/";
            self::createDir($mkDir);
        }
    }

    /**
     * Directory creation
     *
     * @param string $path
     */
    public static function createDir(string $path): void
    {
        if (!is_dir($path) && !mkdir($path) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
    }

    /**
     * Delete directory
     *
     * @param string $path
     *
     * @throws DirectoryException
     * @throws IsNotResource
     */
    public static function deleteDir(string $path): void
    {
        $dir = self::openDir($path);
        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {
                if (DirectoryInfo::isDirExist($path . '/' . $file)) {
                    self::deleteDir($path . '/' . $file);
                } else {
                    FileOperation::delete($path . '/' . $file);
                }
            }
        }
        self::closedir($dir);
        rmdir($path);
    }

    /**
     * Open directory
     *
     * @param string $path
     *
     * @return resource
     * @throws DirectoryException
     */
    public static function openDir(string $path)
    {
        if (!DirectoryInfo::isDirExist($path)) {
            throw new DirectoryException('dir in not exist');
        }

        $handle = opendir($path);

        if ($handle === false) {
            throw new DirectoryException('Cant open dir');
        }

        return $handle;
    }

    /**
     * Close directory
     *
     * @param resource $handle
     *
     * @return void
     * @throws IsNotResource
     */
    public static function closeDir($handle): void
    {
        if (!is_resource($handle)) {
            throw new IsNotResource();
        }

        closedir($handle);
    }
}
