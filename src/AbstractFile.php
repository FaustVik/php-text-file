<?php

namespace FaustVik\Files;

use FaustVik\Files\Exceptions\FileIsNotReadable;
use FaustVik\Files\Exceptions\FileNotFound;
use FaustVik\Files\Helpers\FileInfo;
use FaustVik\Files\Helpers\FileOperation;
use FaustVik\Files\Interfaces\MovingInterface;

/**
 * Class AbstractFile
 * @package FaustVik\Files
 */
abstract class AbstractFile implements MovingInterface
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
        if (!FileInfo::exist($path_to_file)) {
            throw new FileNotFound('Not found file path: ' . $path_to_file);
        }

        if (!is_file($path_to_file)) {
            throw new FileNotFound('Is not file');
        }

        if (!FileInfo::isReadable($path_to_file)) {
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
        return FileInfo::getName(($this->getPathFile()));
    }

    public function getExtension(): ?string
    {
        return FileInfo::getExtension($this->getPathFile());
    }

    /**
     * @param string $newName
     *
     * @return bool
     * @throws FileNotFound
     */
    public function rename(string $newName): bool
    {
       $new_path = FileOperation::rename($this->getPathFile(), $newName);

       if (!$new_path){
           return false;
       }

        $this->setPathToFile($new_path);
        return true;
    }

    /**
     * @param string $newPath
     *
     * @return bool
     * @throws Exceptions\FileException
     */
    public function copy(string $newPath): bool
    {
        return FileOperation::copy($this->getPathFile(), $newPath);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return FileOperation::delete($this->getPathFile());
    }

    /**
     * @param string $path_to_file
     */
    protected function setPathToFile(string $path_to_file): void
    {
        $this->path_to_file = $path_to_file;
    }
}
