<?php

namespace FaustVik\Files\Helpers;

class FileMode
{
    public const ONLY_READ_START_FILE = 'r';
    public const ONLY_READ_END_FILE   = 'r+';
    public const ONLY_READ_BINARY ='rb';

    public const WRITE_ONLY = 'w';
    public const WRITE_READ = 'w+';

    public const WRITE_ONLY_CAN_CREATE = 'a';
    public const WRITE_READ_CAN_CREATE = 'a+';

    public static function isValidMode(string $mode): bool
    {
        $listMode = [
            self::ONLY_READ_START_FILE,
            self::ONLY_READ_END_FILE,
            self::WRITE_ONLY,
            self::WRITE_READ,
            self::WRITE_ONLY_CAN_CREATE,
            self::WRITE_READ_CAN_CREATE,
            self::ONLY_READ_BINARY,
        ];

        return in_array($mode, $listMode, true);
    }
}
