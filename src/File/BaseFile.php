<?php

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Exceptions\FileIsNotReadable;
use FaustVik\Files\Exceptions\FileNotFound;
use FaustVik\Files\Helpers\FileInfo;
use FaustVik\Files\Helpers\FileMode;
use FaustVik\Files\Interfaces\LockingInterface;

class BaseFile extends AbstractFile
{
    /**@var string $pathFile */
    protected $pathFile;

    /**@var ?LockingInterface $lockHelper */
    protected $lockHelper = null;

    protected $useLockFile = false;

    protected $skipEmptyLine = false;

    /**
     * @param string $pathFile
     * @param bool   $create
     *
     * @throws FileIsNotReadable
     * @throws FileNotFound
     * @throws FileException
     */
    public function __construct(string $pathFile, bool $create = false)
    {
        if ($create && !$this->create($pathFile)) {
            throw new FileException('Can\'t create file');
        }

        $this->checkFile($pathFile);
        $this->setPathFile($pathFile);
    }

    /**
     * @param string $path
     *
     * @return void
     * @throws FileIsNotReadable
     * @throws FileNotFound
     */
    protected function checkFile(string $path): void
    {
        if (!FileInfo::exist($path)) {
            throw new FileNotFound('Not found file path: ' . $path);
        }

        if (!is_file($path)) {
            throw new FileNotFound('Is not file');
        }

        if (!FileInfo::isReadable($path)) {
            throw new FileIsNotReadable('is not readable file');
        }
    }

    /**
     * @param string $path
     * @param string $mode
     *
     * @return resource
     * @throws FileException
     */
    protected function openFile(string $path, string $mode)
    {
        $handle = fopen($path, $mode);

        if ($handle === false) {
            throw new FileException('Cant open file');
        }

        return $handle;
    }

    /**
     * @param resource $stream
     *
     * @return bool
     * @throws FileException
     */
    protected function closeFile($stream): bool
    {
        if (!is_resource($stream)) {
            throw new FileException('Is not type resource');
        }

        return fclose($stream);
    }

    public function enableLockFile(): self
    {
        $this->useLockFile = true;
        return $this;
    }

    /**
     * @param LockingInterface|null $locking
     *
     * @return $this
     */
    public function setLockFile(LockingInterface $locking = null): self
    {
        if ($locking === null) {
            $this->lockHelper = new LockDefault();
        } else {
            $this->lockHelper = $locking;
        }

        return $this;
    }

    protected function locking($stream, int $operation): void
    {
        if (!$this->useLockFile) {
            return;
        }

        if ($this->lockHelper === null) {
            $this->setLockFile();
        }

        if (!$this->lockHelper->lock($stream, $operation)) {
            throw new FileException('Can\'t lock file');
        }
    }

    protected function unlocking($stream): void
    {
        if (!$this->useLockFile) {
            return;
        }

        if ($this->lockHelper === null) {
            $this->setLockFile();
        }

        if (!$this->lockHelper->unlock($stream)) {
            throw new FileException('Can\'t unlock file');
        }
    }

    public function clear(): bool
    {
        $handle = $this->openFile($this->pathFile, FileMode::WRITE_READ);
        $result = ftruncate($handle, 0);
        $this->closeFile($handle);
        return $result;
    }

    /**
     * @return string
     */
    public function getPathFile(): string
    {
        return $this->pathFile;
    }

    /**
     * @param string $pathFile
     */
    public function setPathFile(string $pathFile): void
    {
        $this->pathFile = $pathFile;
    }

    public function create(string $path): bool
    {
        $handle = $this->openFile($path, FileMode::WRITE_ONLY);
        return $this->closeFile($handle);
    }

    public function skipEmptyLine(): self
    {
        $this->skipEmptyLine = true;
        return $this;
    }
}
