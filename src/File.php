<?php

namespace FaustVik\Files;

/**
 * Class File
 * @package FaustVik\Files
 */
class File extends AbstractFile
{
    /**
     * @return array
     */
    public function readFileToArray(): array
    {
        $flags = null;

        if ($this->flag_skip_empty_line) {
            $flags = FILE_SKIP_EMPTY_LINES;
        }

        if ($this->flag_ignore_new_line) {
            if (!$flags) {
                $flags = FILE_IGNORE_NEW_LINES;
            } else {
                $flags |= FILE_IGNORE_NEW_LINES;
            }
        }

        $array = file($this->path_to_file, $flags);

        if ($array === false) {
            return [];
        }

        return $array;
    }

    /**
     * @param null $offset
     * @param null $length
     *
     * @return string
     */
    public function readFileToString($offset = null, $length = null): string
    {
        if ($length) {
            $string = file_get_contents($this->path_to_file, false, null, $offset, $length);
        } else {
            $string = file_get_contents($this->path_to_file, false, null, $offset);
        }

        if ($string === false) {
            return '';
        }

        return $string;
    }

    /**
     * Save file
     *
     * @return bool
     */
    public function save(): bool
    {
        // TODO: Implement save() method.
    }

    /**
     * Save data in new file
     *
     * @param string $path
     *
     * @return bool
     */
    public function saveToNewFile(string $path): bool
    {
        // TODO: Implement saveToNewFile() method.
    }
}