<?php

declare(strict_types=1);

namespace FaustVik\Files\Helpers;

/**
 * Class FileMode
 * @package FaustVik\Files\Helpers
 */
class FileMode
{
    public const ONLY_READ         = 'r';
    public const READ_WRITE        = 'r+';
    public const ONLY_READ_BINARY  = 'rb';
    public const READ_WRITE_BINARY = 'rb+';

    public const WRITE_TRUNC_ONLY        = 'w';
    public const WRITE_READ_TRUNC        = 'w+';
    public const WRITE_TRUNC_ONLY_BINARY = 'wb';
    public const WRITE_READ_TRUNC_BINARY = 'wb+';

    public const WRITE_APPEND_ONLY        = 'a';
    public const WRITE_READ_APPEND        = 'ab+';
    public const WRITE_APPEND_ONLY_BINARY = 'ab';
    public const WRITE_READ_APPEND_BINARY = 'ab+';

    public const WRITE_ONLY_NEW_FILE        = 'x';
    public const WRITE_READ_ONLY_NEW_FILE   = 'x+';
    public const WRITE_ONLY_NEW_FILE_BINARY = 'xb';
    public const WRITE_READ_ONLY_NEW_BINARY = 'xb+';

    public const WRITE_ONLY_START_FILE        = 'c';
    public const WRITE_READ_START_FILE        = 'c+';
    public const WRITE_ONLY_START_FILE_BINARY = 'cb';
    public const WRITE_READ_START_FILE_BINARY = 'cb+';

    public const CLOSE_ON_EXEC = 'e';

    /**
     * @param string $mode
     *
     * @return bool
     */
    public static function isValidMode(string $mode): bool
    {
        $listMode = [
            self::ONLY_READ,
            self::READ_WRITE,
            self::ONLY_READ_BINARY,
            self::READ_WRITE_BINARY,
            self::WRITE_TRUNC_ONLY,
            self::WRITE_READ_TRUNC,
            self::WRITE_TRUNC_ONLY_BINARY,
            self::WRITE_READ_TRUNC_BINARY,
            self::WRITE_APPEND_ONLY,
            self::WRITE_READ_APPEND,
            self::WRITE_APPEND_ONLY_BINARY,
            self::WRITE_READ_APPEND_BINARY,
            self::WRITE_ONLY_NEW_FILE,
            self::WRITE_READ_ONLY_NEW_FILE,
            self::WRITE_ONLY_NEW_FILE_BINARY,
            self::WRITE_READ_ONLY_NEW_BINARY,
            self::WRITE_ONLY_START_FILE,
            self::WRITE_READ_START_FILE,
            self::WRITE_ONLY_START_FILE_BINARY,
            self::WRITE_READ_START_FILE_BINARY,
            self::CLOSE_ON_EXEC,
        ];

        return in_array($mode, $listMode, true);
    }
}
