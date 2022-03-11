<?php

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\FileException;

class FileLocker
{
    /**@var array $loc_files */
    protected static $loc_files = [];

    /**
     * @param resource $stream
     * @param bool     $wait
     *
     * @return false|mixed
     * @throws FileException
     */
    public static function lock($stream, bool $wait)
    {
        if (!$stream || !is_resource($stream)) {
            throw new FileException('Is not type resource');
        }

        if ($wait) {
            $lock = flock($stream, LOCK_EX);
        } else {
            $lock = flock($stream, LOCK_EX | LOCK_NB);
        }

        if (!$lock) {
            throw new FileException('Can\'t lock file');
        }

        self::$loc_files[$stream] = $stream;
        return $stream;
    }

    public static function unlockFile($file_name)
    {
        fclose(self::$loc_files[$file_name]);
        @unlink($file_name);
        unset(self::$loc_files[$file_name]);
    }
}
