<?php

namespace FaustVik\Files;

use FaustVik\Files\exceptions\FileIsNotReadable;
use FaustVik\Files\exceptions\FileNotFound;
use FaustVik\Files\interfaces\InfoFileInterface;
use FaustVik\Files\interfaces\MoveInterface;

/**
 * Class AbstractFile
 * @package FaustVik\Files
 */
abstract class AbstractFile implements InfoFileInterface, MoveInterface
{
    /**@var string $path_to_file */
    protected $path_to_file;

    /**
     * AbstractFile constructor.
     *
     * @param string $path_to_file
     *
     * @throws FileNotFound
     * @throws FileIsNotReadable
     */
    public function __construct(string $path_to_file)
    {
        $this->checkFile($path_to_file);
        $this->setPathToFile($path_to_file);
    }

    /**
     * @param string $path_to_file
     *
     * @throws FileIsNotReadable
     * @throws FileNotFound
     */
    protected function checkFile(string $path_to_file): void
    {
        if (!file_exists($path_to_file)) {
            throw new FileNotFound('Not found file path: ' . $path_to_file);
        }

        if (!is_file($path_to_file)) {
            throw new FileNotFound('Is not file');
        }

        if (!is_readable($path_to_file)) {
            throw new FileIsNotReadable('Not readable file');
        }
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
        return basename($this->getPathFile());
    }

    /**
     * the size of the file in bytes
     *
     * @return int
     */
    public function getSize(): int
    {
        return filesize($this->getPathFile());
    }

    public function getExtension(): string
    {
        $info = pathinfo($this->getPathFile());
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

    /**
     * @param string $new_name
     *
     * @return bool
     */
    public function rename(string $new_name): bool
    {
        $new_path = str_replace($this->getName(), $new_name, $this->getPathFile());
        $res      = rename($this->getPathFile(), $new_path);

        $this->setPathToFile($new_path);
        return $res;
    }

    /**
     * @param string $new_path
     *
     * @return bool
     */
    public function copy(string $new_path): bool
    {
        return copy($this->getPathFile(), $new_path);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return unlink($this->getPathFile());
    }

    /**
     * @param string $path_to_file
     */
    protected function setPathToFile(string $path_to_file): void
    {
        $this->path_to_file = $path_to_file;
    }
}