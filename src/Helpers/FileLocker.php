<?php

declare(strict_types=1);

namespace FaustVik\Files\Helpers;

use FaustVik\Files\Exceptions\FileException;

/**
 * Class FileLocker
 * @package FaustVik\Files\Helpers
 */
class FileLocker
{
    /**@var array $stackFilesIsLocked */
    protected static $stackFilesIsLocked = [];

    /**
     * @param resource $handle
     * @param string   $path
     * @param int      $operation
     *
     * @return resource
     * @throws FileException
     */
    public static function lock($handle, string $path, int $operation)
    {
        if (!$handle || !is_resource($handle)) {
            throw new FileException('Is not type resource');
        }

        $lockResult = flock($handle, $operation);

        if (!$lockResult) {
            throw new FileException('Can\'t lock file');
        }

        self::$stackFilesIsLocked[$path] = true;
        return $handle;
    }

    /**
     * @param resource $handle
     * @param string   $path
     *
     * @return resource
     * @throws FileException
     */
    public static function unlockFile($handle, string $path)
    {
        if (!$handle || !is_resource($handle)) {
            throw new FileException('Is not type resource');
        }

        $lockResult = flock($handle, LOCK_UN);

        if (!$lockResult) {
            throw new FileException('Can\'t unlock file');
        }

        unset(self::$stackFilesIsLocked[$path]);
        return $handle;
    }
}
