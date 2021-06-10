<?php

namespace FaustVik\Files;

/**
 * Class AbstractFile
 * @package FaustVik\Files
 */
abstract class AbstractFile implements IOInterface, FileInterface
{
    /**@var string $path_to_file */
    protected $path_to_file;

    /**@var bool $flag_skip_empty_line */
    protected $flag_skip_empty_line = false;
    /**@var bool $flag_ignore_new_line */
    protected $flag_ignore_new_line = false;

    /**
     * File constructor.
     *
     * @param string $path_to_file
     *
     * @throws FIleException
     */
    public function __construct(string $path_to_file)
    {
        if (!file_exists($path_to_file)) {
            throw new FIleException('Not found file path: ' . $path_to_file);
        }

        if (!is_file($path_to_file)) {
            throw new FIleException('Is not file');
        }

        if (!is_readable($path_to_file)) {
            throw new FIleException('No readable file');
        }

        $this->path_to_file = $path_to_file;
    }

    /**
     * @return string
     */
    public function getPathFile(): string
    {
        return $this->path_to_file;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return basename($this->path_to_file);
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return filesize($this->path_to_file);
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
}