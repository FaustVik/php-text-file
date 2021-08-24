<?php

namespace FaustVik\Files;

/**
 * Class TextFile
 * @package FaustVik\Files
 */
class TextFile extends AbstractFile
{
    /**@var bool $flag_skip_empty_line */
    protected $flag_skip_empty_line = false;

    /**@var bool $flag_ignore_new_line */
    protected $flag_ignore_new_line = false;

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
        $string = file_get_contents($this->path_to_file, false, null, $offset, $length);

        if ($string === false) {
            return '';
        }

        return $string;
    }

    /**
     * @return $this
     */
    public function skipEmptyLine(): AbstractFile
    {
        $this->flag_skip_empty_line = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function ignoreNewLines(): AbstractFile
    {
        $this->flag_ignore_new_line = true;
        return $this;
    }

    /**
     * @param string $text
     */
    public function overwriteToFile(string $text): void
    {
        file_put_contents($this->path_to_file, $text);
    }

    /**
     * @param string|null $text
     */
    public function appendToFile(string $text): void
    {
        file_put_contents($this->path_to_file, $text, FILE_APPEND);
    }

    /**
     * @param string $path_to_new_file
     */
    public function saveToNewFile(string $path_to_new_file): void
    {
        $text = $this->readFileToString();
        file_put_contents($path_to_new_file, $text);
    }
}