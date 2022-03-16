<?php

namespace FaustVik\Files\Exceptions\File;

use FaustVik\Files\Exceptions\FileException;

/**
 * Class FIleIsNotWriteable
 * @package FaustVik\Files\Exceptions\File
 */
class FIleIsNotWriteable extends FileException
{
    public function __construct(string $message = "")
    {
        parent::__construct(sprintf("File is not writeable:  %s", $message));
    }
}
