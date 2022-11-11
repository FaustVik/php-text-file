<?php

declare(strict_types=1);

namespace FaustVik\Files\Helpers;

use function basename;
use function clearstatcache;
use function file_exists;
use function filemtime;
use function fileowner;
use function filesize;
use function is_readable;
use function is_writable;
use function pathinfo;
use function posix_getpwuid;

/**
 * Class FileInfo
 * @package FaustVik\Files\Helpers
 */
final class FileInfo
{
    /**
     * @param string $path
     *
     * @return string
     */
    public static function getName(string $path): string
    {
        return basename($path);
    }

    /**
     * the size of the file in bytes
     *
     * @param string $path
     *
     * @return int
     */
    public static function getSize(string $path): int
    {
        self::clearStatCache($path);
        return filesize($path);
    }

    /**
     * @param string $path
     *
     * @return string|null
     */
    public static function getExtension(string $path): ?string
    {
        self::clearStatCache($path);
        $info = pathinfo($path);
        return $info['extension'] ?? null;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public static function isWritable(string $path): bool
    {
        if (!self::exist($path)) {
            return false;
        }

        return is_writable($path);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public static function isReadable(string $path): bool
    {
        if (!self::exist($path)) {
            return false;
        }

        return is_readable($path);
    }

    /**
     * @param string $path
     *
     * @return array|false
     */
    public static function getFileOwner(string $path)
    {
        if (!self::exist($path)) {
            return false;
        }

        return posix_getpwuid(fileowner($path));
    }

    /**
     * @param bool   $clear_realpath_cache
     * @param string $filename
     *
     * @return void
     */
    public static function clearStatCache(string $filename, bool $clear_realpath_cache = true): void
    {
        clearstatcache($clear_realpath_cache, $filename);
    }

    /**
     * @param string $path path to file
     *
     * @return bool
     */
    public static function exist(string $path): bool
    {
        self::clearStatCache($path);
        return file_exists($path);
    }

    /**
     * @param string $path
     *
     * @return false|int
     */
    public static function getFileTime(string $path)
    {
        if (!self::exist($path)) {
            return false;
        }

        return filemtime($path);
    }
}
