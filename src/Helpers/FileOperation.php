<?php

declare(strict_types=1);

namespace FaustVik\Files\Helpers;

use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Exceptions\FileNotFound;

class FileOperation
{
    /**
     * Удаление файла
     *
     * @param string $path
     *
     * @return bool
     */
    public static function delete(string $path): bool
    {
        if (!FileInfo::exist($path)) {
            return false;
        }

        return unlink($path);
    }

    /**
     * @param string $from
     * @param string $to
     * @param bool   $deleteOriginal
     *
     * @return bool
     * @throws FileException
     */
    protected static function moving(string $from, string $to, bool $deleteOriginal = false): bool
    {
        if ($from === '') {
            throw new FileException('Not set path for from');
        }

        if ($to === '') {
            throw new FileException('Not set path for to');
        }

        if (!FileInfo::exist($from)) {
            throw new FileException('Not found file ' . $from);
        }

        if (!copy($from, $to)) {
            return false;
        }

        if ($deleteOriginal && !self::delete($from)) {
            throw new FileException('Can\'t delete file '.$from);
        }

        return true;
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return bool
     * @throws FileException
     */
    public static function copy(string $from, string $to): bool
    {
        return self::moving($from, $to);
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return bool
     * @throws FileException
     */
    public static function move(string $from, string $to): bool
    {
        return self::moving($from, $to, true);
    }

    /**
     * @param string $path
     * @param string $newName
     *
     * @return false|string
     * @throws FileNotFound
     */
    public static function rename(string $path, string $newName)
    {
        if (!FileInfo::exist($path)){
           throw new FileNotFound($path);
        }

        $nameOld = FileInfo::getName($path);
        $newPath = str_replace($nameOld, $newName, $path);

        if (!rename($path, $newPath)) {
            return false;
        }

        return $newPath;
    }
}
