<?php

namespace FaustVik\Files;

use FaustVik\Files\Interfaces\IoTextInterface;

/**
 * Class TextFile
 * @package FaustVik\Files
 */
class TextFile extends AbstractFile implements IoTextInterface
{
    /**@var bool $flag_skip_empty_line */
    protected $flag_skip_empty_line = false;

    /**@var bool $flag_ignore_new_line */
    protected $flag_ignore_new_line = false;

    /**
     * @return array
     */
    public function readToArray(): array
    {
        $flags = $this->getFlags();
        $array = file($this->path_to_file, $flags);

        if ($array === false) {
            return [];
        }

        return $array;
    }

    protected function getFlags(): ?int
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

        return $flags;
    }

    /**
     * @param null $offset
     * @param null $length
     *
     * @return string
     */
    public function readToString($offset = null, $length = null): string
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
    public function overWrite(string $text): void
    {
        $this->write($text, false);
    }

    /**
     * @param string|null $text
     */
    public function write(string $text): void
    {
        $this->write($text);
    }

    /**
     * @param string $path_to_new_file
     */
    public function saveToNewFile(string $path_to_new_file): void
    {
        $text = $this->readToString();
        $this->write($text, false, $path_to_new_file);
    }

    protected function write(string $text, bool $add_to_file = true, ?string $another_path_file = null)
    {
        $path = $another_path_file ?? $this->path_to_file;

        if ($add_to_file) {
            return file_put_contents($path, $text, FILE_APPEND);
        }

        return file_put_contents($path, $text);
    }
}
