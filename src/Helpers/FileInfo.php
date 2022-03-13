<?php

namespace FaustVik\Files\Helpers;

class FileInfo
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
        self::clearStatCache(true, $path);
        return filesize($path);
    }

    /**
     * @param string $path
     *
     * @return string|null
     */
    public static function getExtension(string $path): ?string
    {
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
    public static function isReadable(string $path):bool
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
    public static function clearStatCache(bool $clear_realpath_cache = false, string $filename):void
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
        self::clearStatCache(true, $path);
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
