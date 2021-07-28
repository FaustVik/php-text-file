<?php

namespace FaustVik\Files;

/**
 * Class AbstractFile
 * @package FaustVik\Files
 */
abstract class AbstractFile implements FileInterface
{
    /**@var string $path_to_file */
    protected $path_to_file;

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
     * the size of the file in bytes
     *
     * @return int
     */
    public function getSize(): int
    {
        return filesize($this->path_to_file);
    }

    public function getExtension(): string
    {
        $info = pathinfo($this->path_to_file);
        return $info['extension'];
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public static function exist(string $path): bool
    {
        return file_exists($path) && is_file($path) && is_readable($path);
    }
}