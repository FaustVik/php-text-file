<?php

declare(strict_types=1);

namespace FaustVik\Files\Directory;

use function is_dir;
use function realpath;
use function scandir;

/**
 * Class DirectoryInfo
 * @package FaustVik\Files\Directory
 */
final class DirectoryInfo
{
    public const SORT_ASC  = SCANDIR_SORT_ASCENDING;
    public const SORT_DESC = SCANDIR_SORT_DESCENDING;
    public const NO_SORT   = SCANDIR_SORT_NONE;

    /**
     * Directory scan
     *
     * @param string $path
     * @param int    $sort
     *
     * @return array
     */
    public static function scan(string $path, int $sort = self::SORT_ASC): array
    {
        if (!self::isDirExist($path)) {
            return [];
        }

        $realPath = self::getRealPath($path);

        if ($realPath === false) {
            return [];
        }

        $list = scandir($realPath, $sort);

        if (!$list) {
            return [];
        }

        return $list;
    }

    /**
     * Does a directory exist and is it a directory
     *
     * @param string $folder
     *
     * @return bool
     */
    public static function isDirExist(string $folder): bool
    {
        $path = self::getRealPath($folder);
        return ($path !== false && is_dir($path));
    }

    /**
     * Get realpath
     *
     * @param string $path
     *
     * @return false|string
     */
    public static function getRealPath(string $path)
    {
        return realpath($path);
    }
}
