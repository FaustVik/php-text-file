<?php

declare(strict_types=1);

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Exceptions\FileNotFound;
use FaustVik\Files\Helpers\FileOperation;
use FaustVik\Files\Interfaces\MovingInterface;

class Moving implements MovingInterface
{
    /**@var BaseFile $file */
    protected $file;

    public function __construct(BaseFile $file)
    {
        $this->file = $file;
    }

    /**
     * @throws FileException
     */
    public function copy(string $newPath): bool
    {
        return FileOperation::copy($this->file->getPathFile(), $newPath);
    }

    /**
     * @param string $newName
     *
     * @return bool
     * @throws FileNotFound
     */
    public function rename(string $newName): bool
    {
        $newPath = FileOperation::rename($this->file->getPathFile(), $newName);

        if (!$newPath) {
            return false;
        }

        $this->file->setPathFile($newPath);
        return true;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return FileOperation::delete($this->file->getPathFile());
    }

    /**
     * @param string $newPath
     *
     * @return bool
     * @throws FileException
     */
    public function move(string $newPath): bool
    {
        if (!FileOperation::move($this->file->getPathFile(), $newPath)) {
            return false;
        }

        $this->file->setPathFile($newPath);
        return true;
    }
}
